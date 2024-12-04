<?php

namespace App\Services;

use App\CustomEncoder;
use App\Mail\AdminMail;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\MT527CollateralParty;
use App\Models\MT527Data;
use App\Models\MT527General;
use App\Models\MT527TransactionDetails;
use App\Models\MT558CollateralParty;
use App\Models\MT558Data;
use App\Models\MT558General;
use App\Models\MT558Linkage;
use App\Models\MT558Status;
use App\Models\MT558TransactionDetail;
use App\Models\MT569Collateral;
use App\Models\MT569CollateralParty;
use App\Models\MT569Data;
use App\Models\MT569FinancialSummaryDetails;
use App\Models\MT569GeneralInformation;
use App\Models\MT569SecurityDetails;
use App\Models\MT569Summary;
use App\Models\MT569TransactionDetails;
use App\Models\MT569ValuationDetails;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;


class MTService
{
    protected $pdf;

    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }


    public function processMessage($message)
    {
        if (strpos($message, '{2:O527') !== false) {
            $sections = $this->parseMTFile($message);
            $general_mt527 = null;
            foreach ($sections as $section => $content) {
                switch ($section) {
                    case 'GENL':
                        $general_mt527 = $this->storeMT527General($content);
                        break;
                    case strpos($section, 'COLLPRTY') === 0:
                        if ($general_mt527) {
                            $this->storeMt527ColleteralParties($content, $general_mt527->id);
                        }
                        break;
                    case strpos($section, 'DEALTRAN') === 0:
                        if ($general_mt527) {
                            $this->storeMT527TransactionDetails($content, $general_mt527->id);
                        }
                        break;
                }
            }
        } elseif (strpos($message, '{2:O558') !== false) {
            $sections = $this->parseMTFile($message);
            ///return $sections;
            $general_mt558 = null;
            foreach ($sections as $section => $content) {
                switch ($section) {
                    case 'GENL':
                        $general_mt558 = $this->storeMT558General($content);
                        throw new \Exception('Unrecognized message type');
                        break;
                    case strpos($section, 'COLLPRTY') === 0:
                        if ($general_mt558) {
                            $this->storeMT558CollateralData($content, $general_mt558->id);
                        }
                        break;
                    case strpos($section, 'STAT') === 0:
                        if ($general_mt558) {
                            $this->storeMT558StatusData($content, $general_mt558->id);
                        }
                        break;
                    case strpos($section, 'LINK') === 0:
                        if ($general_mt558) {
                            $this->storeMT558LinkageData($content, $general_mt558->id);
                        }
                        break;
                    case strpos($section, 'DEALTRAN') === 0:
                        if ($general_mt558) {
                            $this->storeMT558TransactionDetails($content, $general_mt558->id);
                        }
                        break;
                }
            }
        } elseif (strpos($message, '{2:O569') !== false) {
            $sections = $this->parseMTFile($message);
            // return $sections;
            $general = null;
            foreach ($sections as $section => $content) {
                switch ($section) {
                    case 'GENL':
                        $general = $this->storeGeneralInformation($content);
                        break;
                    case strpos($section, 'SUMC') === 0:
                        if ($general) {
                            $this->storeFinanceSummary($content, $general->id);
                        }
                        break;
                    case strpos($section, 'SUMM') === 0:
                        if ($general) {
                            $this->storeSummaryInformation($content, $general->id);
                        }
                        break;
                    case strpos($section, 'COLLPRTY') === 0:
                        // return $this->storeCollateralParty($content,1);
                        if ($general) {
                            $this->storeCollateralParty($content, $general->id);
                        }
                        break;
                    case strpos($section, 'SUME') === 0:
                        if ($general) {
                            $this->storeSummaryCollateral($content, $general->id);
                        }
                        break;
                    case strpos($section, 'TRANSDET') === 0:
                        // return $this->storeTransactionDetail($content, 1);
                        if ($general) {
                            $this->storeTransactionDetail($content, $general->id);
                        }
                        break;
                    case strpos($section, 'VALDET') === 0:
                        if ($general) {
                            $this->storeValuationDetail($content, $general->id);
                        }
                        break;
                    case strpos($section, 'SECDET') === 0:
                        // return $this->storeSecurityDetail($content, 1);
                        if ($general) {
                            $this->storeSecurityDetail($content, $general->id);
                        }
                        break;
                }
            }
        } else {
            throw new \Exception('Unrecognized message type');
        }
    }

    // public function generateMessage($file_type)
    // {
    //     if ($file_type == '527') {
    //         $data = $this->generateMT527(3434);
    //     } elseif ($file_type == '558') {
    //         $data = $this->generateMT558();
    //     } else {
    //         throw new \Exception('Unrecognized message type');
    //     }
    // }

    private function parseMT527($message)
    {
        $fields = [
            'sequence_number' => '/:28E:(.*)/',
            'sender_reference' => '/:20C::SEME\/\/(.*)/',
            'client_reference' => '/:20C::CLCI\/\/(.*)/',
            'collateral_transaction' => '/:20C::SCTR\/\/(.*)/',
            'function_of_message' => '/:23G:(.*)/',
            'request_date' => '/:98A::EXRQ\/\/(.*)/',
            'collateral_intention' => '/:22H::CINT\/\/(.*)/',
            'collateral_type' => '/:22H::COLA\/\/(.*)/',
            'collateral_receive_provide_indicator' => '/:22H::REPR\/\/(.*)/',
            'eligibility' => '/:13B::ELIG\/\/(.*)/',
            'party_a' => '/:95R::PTYA\/CEDE\/(.*)/',
            'collateral_party' => '/:95R::CLPA\/CEDE\/(.*)/',
            'party_b' => '/:95R::PTYB\/CEDE\/(.*)/',
            'transaction_agent' => '/:95R::TRAG\/CEDE\/(.*)/',
            'term' => '/:98B::TERM\/\/(.*)/',
            'trade_amount' => '/:19A::TRAA\/\/EUR(.*),/',
            // 'c_t_request_deposit_trade_events' => '/:22H::COLA\/\/(.*)/', 
        ];

        $data = [];
        foreach ($fields as $key => $regex) {
            if (preg_match($regex, $message, $matches)) {
                $data[$key] = $matches[1];
            }
        }

        return $data;
    }

    private function parseMT558($message)
    {
        $fields = [
            'sequence_number' => '/:28E:(.*)/',
            'sender_reference' => '/:20C::SEME\/\/(.*)/',
            'client_reference' => '/:20C::CLCI\/\/(.*)/',
            'trade_reference' => '/:20C::CLTR\/\/(.*)/',
            'function_of_message' => '/:23G:(.*)/',
            'request_date' => '/:98A::EXRQ\/\/(.*)/',
            'collateral_intention' => '/:22H::CINT\/\/(.*)/',
            'collateral_type' => '/:22H::COLA\/\/(.*)/',
            'collateral_reuse' => '/:22H::REPR\/\/(.*)/',
            'auto_collateralization' => '/:22F::AUTA\/\/(.*)/',
            'eligible_counterparty' => '/:13B::ELIG\/\/(.*)/',
            'party_a' => '/:95R::PTYA\/CEDE\/(.*)/',
            'party_b' => '/:95R::PTYB\/CEDE\/(.*)/',
            'transaction_agent' => '/:95R::TRAG\/CEDE\/(.*)/',
            'instruction_processing' => '/:25D::IPRC\/\/(.*)/',
            'allocation_status' => '/:25D::ALOC\/\/(.*)/',
            'settlement_status' => '/:25D::SETT\/\/(.*)/',
            'collateral_amount' => '/:19A::ALAM\/\/CAD(.*),/',
            'requested_amount' => '/:19A::RALA\/\/CAD(.*),/',
            'estimated_amount' => '/:19A::ESTT\/\/CAD(.*),/',
            'received_amount' => '/:19A::RSTT\/\/CAD(.*),/',
            'related_reference' => '/:20C::RELA\/\/(.*)/',
            'term' => '/:98B::TERM\/\/(.*)/',
            'trade_amount' => '/:19A::TRAA\/\/CAD(.*),/',
            'price' => '/:92A::PRIC\/\/(.*),/',
        ];

        $data = [];
        foreach ($fields as $key => $regex) {
            if (preg_match($regex, $message, $matches)) {
                $data[$key] = $matches[1];
            }
        }

        return $data;
    }

    private function parseMT569($message)
    {
        $fields = [
            'sequence_number' => '/:28E:(.*)/',
            'status_code' => '/:13A::STAT\/\/(.*)/',
            'reference' => '/:20C::SEME\/\/(.*)/',
            'function_of_message' => '/:23G:(.*)/',
            'preparation_date' => '/:98C::PREP\/\/(.*)/',
            'collateral_reuse' => '/:22H::REPR\/\/(.*)/',
            'collateral_status' => '/:22F::STBA\/\/(.*)/',
            'collateral_free' => '/:22F::SFRE\/\/(.*)/',
            'party_a' => '/:95R::PTYA\/CEDE\/(.*)/',
            'total_exposure_amount' => '/:19A::TEXA\/\/CAD(.*),/',
            'total_collateral_received' => '/:19A::TCOR\/\/CAD(.*),/',
            'total_collateral_value' => '/:19A::COVA\/\/CAD(.*),/',
            'margin_amount' => '/:19A::MARG\/\/CAD(.*),/',
            'margin_percentage' => '/:92A::MARG\/\/(.*),/',
            'valuation_date' => '/:98C::VALN\/\/(.*)/',
            'collateral_type' => '/:22F::COLA\/\/(.*)/',
            'total_valuation' => '/:19A::TVOC\/\/CAD(.*),/',
            'total_received' => '/:19A::TVRC\/\/CAD(.*),/',
            'eligibility' => '/:13B::ELIG\/\/(.*)/',
            'party_b' => '/:95R::PTYB\/CEDE\/(.*)/',
            'transaction_agent' => '/:95R::TRAG\/CEDE\/(.*)/',
            'trade_reference' => '/:20C::CLTR\/\/(.*)/',
            'trade_counter_reference' => '/:20C::TCTR\/\/(.*)/',
            'term' => '/:98B::TERM\/\/(.*)/',
            'execution_request_date' => '/:98A::EXRQ\/\/(.*)/',
            'transaction_amount' => '/:19A::TEXA\/\/CAD(.*),/',
            'transaction_collateral' => '/:19A::TCOR\/\/CAD(.*),/',
            'collateral_value' => '/:19A::COVA\/\/CAD(.*),/',
            'margin_amount2' => '/:19A::MARG\/\/CAD(.*),/',
            'transaction_fees' => '/:19A::TCFA\/\/CAD(.*),/',
            'price' => '/:92A::PRIC\/\/(.*),/',
            'transaction_type' => '/:25D::TREX\/\/(.*)/',
            'valuation_date2' => '/:98C::VALN\/\/(.*)/',
            'market_price' => '/:92A::PRIC\/\/(.*),/',
            'accrued_interest' => '/:19A::COVA\/\/CAD(.*),/',
            'market_value' => '/:19A::TVOC\/\/CAD(.*),/',
            'exchange_rate' => '/:19A::TCFA\/\/CAD(.*),/',
            'valuation_factor' => '/:92A::MARG\/\/(.*),/',
        ];

        $data = [];
        foreach ($fields as $key => $regex) {
            if (preg_match($regex, $message, $matches)) {
                $data[$key] = $matches[1];
            }
        }

        return $data;
    }


    public function generate527pdfToAws($data, $tripaty_agent)
    {
        try {
            $pdf = $this->pdf->loadView('mt527', compact('data'));
            $pdfContent = $pdf->output();
            $file_name = now()->format('Y-m-d_H:i:s');
            $fileName = 'repo/outflow/yield_generic/527/' . now()->format("m-Y") . '/' . $file_name . '_' . $tripaty_agent . '_MT527.pdf';
            $trade = CTTradeRequestOfferDeposit::find($data->id);
            if ($trade) {
                $trade->file_pdf_generated = $fileName;
                $trade->file_generated_count += 1;
                $trade->save();
            }
            // TODO:: read from aws CTTradeRequestOfferDeposit->file_pdf_generated /// hold the file update  file_count_generate hold the number of times file is generated
            Storage::disk('s3')->put($fileName, $pdfContent);
        } catch (\Throwable $th) {
            $to  = explode(',', env('ERROR_EMAILS_TO'));
            $payload = 'Generate MT527PDF';
            Mail::to($to)->queue(new AdminMail([
                'subject' => 'Failed to generate mt527',
                'message' => $th->getMessage(),
                'payload' => $payload,
            ]));
        }
    }
    public function generate527AndPushToAws($data)
    {
        try {

            $file_name = now()->format('Y-m-d_H:i:s');

            $path = 'repo/outflow/yield_generic/527/' . now()->format("Y") . '/' . now()->format("m") . '/' . now()->format("d") . '/' . $file_name . '_MT527.json';
            Storage::disk('s3')->put($path, json_encode($data));

            $trade = CTTradeRequestOfferDeposit::find($data->id);
            if ($trade) {
                $trade->file_csv_generated = $path;
                $trade->file_generated_count += 1;
                $trade->save();
            }
        } catch (\Throwable $th) {
            $to  = explode(',', env('ERROR_EMAILS_TO'));
            $payload = 'Generate MT527PDF';
            Mail::to($to)->queue(new AdminMail([
                'subject' => 'Failed to generate mt527',
                'message' => $th->getMessage(),
                'payload' => $payload,
            ]));
        }
    }
    private function generateMT558() {}

    private function parseMTFile($swiftMessage)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($swiftMessage));
        $sections = [];
        $currentSection = null;
        $currentTransdetIndex = 1;

        foreach ($lines as $line) {
            if (strpos($line, ":16R:") === 0) {
                $currentSection = substr($line, 5);
                if ($currentSection === 'STAT') {
                    if (isset($sections['STAT' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'STAT' . $currentTransdetIndex;
                }
                if ($currentSection === 'LINK') {
                    if (isset($sections['LINK' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'LINK' . $currentTransdetIndex;
                }
                if ($currentSection === 'DEALTRAN') {
                    if (isset($sections['DEALTRAN' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'DEALTRAN' . $currentTransdetIndex;
                }
                if ($currentSection === 'SUMC') {
                    if (isset($sections['SUMC' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'SUMC' . $currentTransdetIndex;
                }
                if ($currentSection === 'SUME') {
                    if (isset($sections['SUME' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'SUME' . $currentTransdetIndex;
                }
                if ($currentSection === 'COLLPRTY') {
                    if (isset($sections['COLLPRTY' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'COLLPRTY' . $currentTransdetIndex;
                }
                if ($currentSection === 'SUMM') {
                    if (isset($sections['SUMM' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'SUMM' . $currentTransdetIndex;
                }
                if ($currentSection === 'TRANSDET') {
                    if (isset($sections['TRANSDET' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'TRANSDET' . $currentTransdetIndex;
                }
                if ($currentSection === 'VALDET') {
                    if (isset($sections['VALDET' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'VALDET' . $currentTransdetIndex;
                }
                if ($currentSection === 'SECDET') {
                    if (isset($sections['SECDET' . $currentTransdetIndex])) {
                        $currentTransdetIndex++;
                    }
                    $currentSection = 'SECDET' . $currentTransdetIndex;
                }
                if (!isset($sections[$currentSection])) {
                    $sections[$currentSection] = [];
                }
            } elseif (strpos($line, ":16S:") === 0 && $currentSection !== null) {
                $currentSection = null;
            } elseif ($currentSection !== null) {
                $sections[$currentSection][] = $line;
            }
        }

        return $sections;
    }

    protected function storeGeneralInformation($data)
    {
        $genlData = $this->parseGenlSection($data);
        // return $genlData;

        return MT569GeneralInformation::create([
            'sender_reference' => $genlData['msg_ref'] ?? null,
            'sequence_number' => $genlData['sequence_number'] ?? null,
            'statement_number' => $genlData['function_code'] ?? null,
            'function_of_message' => $genlData['is_part_of'] ?? null,
            'prep_date' => isset($genlData['prep_date']) ? Carbon::parse($genlData['prep_date']) : null,
            'collateral_receive_provide_indicator' => $genlData['status'] ?? null,
            'statement_basis_indicator' => $genlData['report_type'] ?? null,
            'statement_frequency_indicator' => $genlData['safekeeping_reference'] ?? null,
        ]);
    }

    protected function storeSummaryInformation($data, $general_id)
    {
        $summaryData = $this->parseSummarySection($data);
        // return $summaryData;

        return MT569Summary::create([
            'm_t569_general_information_id' => $general_id,
            'total_exposure_amount' => $this->correctDecimal($summaryData['total_exposure'] ?? null),
            'total_collateral_required' => $this->correctDecimal($summaryData['total_collateral'] ?? null),
            'collateral_value' => $this->correctDecimal($summaryData['total_covered'] ?? null),
            'margin_amount' => $this->correctDecimal($summaryData['margin'] ?? null),
            'total_valuation' => $this->correctDecimal($summaryData['total_valuation'] ?? null),
            'valuation_date' => isset($summaryData['valuation_date']) ? Carbon::parse($summaryData['valuation_date']) : null,
        ]);
    }

    protected function storeCollateralParty($data, $general_id)
    {
        $collateralPartyData = $this->parseCollateralPartySection($data);
        // return $collateralPartyData;

        return MT569CollateralParty::create([
            'm_t569_general_information_id' => $general_id,
            'party_id' => $collateralPartyData['party_id'] ?? null,
        ]);
    }

    protected function storeSummaryCollateral($data, $general_id)
    {
        $summaryCollateralData = $this->parseSummaryCollateralSection($data);
        // return $summaryCollateralData;

        return MT569Collateral::create([
            'm_t569_general_information_id' => $general_id,
            'total_exposure_amount' => $this->correctDecimal($summaryCollateralData['total_exposure'] ?? null),
            'total_collateral_required' => $this->correctDecimal($summaryCollateralData['total_collateral'] ?? null),
            'collateral_value' => $this->correctDecimal($summaryCollateralData['total_covered'] ?? null),
            'margin_amount' => $this->correctDecimal($summaryCollateralData['margin'] ?? null),
            'total_valuation' => $this->correctDecimal($summaryCollateralData['total_valuation'] ?? null),
            'valuation_margin' => $this->correctDecimal($summaryCollateralData['valuation_margin'] ?? null),
        ]);
    }

    protected function storeTransactionDetail($data, $general_id)
    {
        $transactionDetailData = $this->parseTransactionDetailSection($data);

        return MT569TransactionDetails::create([
            'm_t569_general_information_id' => $general_id,
            'collateral_transaction_ref' => $transactionDetailData['collateral_transaction_ref'] ?? null,
            'transaction_count' => $transactionDetailData['transaction_count'] ?? null,
            'term_type' => $transactionDetailData['term_type'] ?? null,
            'settlement_date' => isset($transactionDetailData['settlement_date']) ? Carbon::parse($transactionDetailData['settlement_date']) : null,
            'total_exposure_amount' => $this->correctDecimal($transactionDetailData['total_exposure_amount'] ?? null),
            'total_collateral_required' => $this->correctDecimal($transactionDetailData['total_collateral_required'] ?? null),
            'collateral_value' => $this->correctDecimal($transactionDetailData['collateral_value'] ?? null),
            'margin_amount' => $this->correctDecimal($transactionDetailData['margin_amount'] ?? null),
            'total_cash_flow' => $this->correctDecimal($transactionDetailData['total_cash_flow'] ?? null),
            'price' => $transactionDetailData['price'] ?? null,
            'valuation_margin' => $this->correctDecimal($transactionDetailData['valuation_margin'] ?? null),
            'transaction_extension' => $transactionDetailData['transaction_extension'] ?? null,
        ]);
    }

    protected function storeValuationDetail($data, $general_id)
    {
        $valuationDetailData = $this->parseValuationDetailSection($data);

        return MT569ValuationDetails::create([
            'm_t569_general_information_id' => $general_id,
            'collateral_flag' => $valuationDetailData['collateral_flag'] ?? null,
            'security_flag' => $valuationDetailData['security_flag'] ?? null,
            'settlement_date' => isset($valuationDetailData['settlement_date']) ? Carbon::parse($valuationDetailData['settlement_date']) : null,
            'market_price' => $this->correctDecimal($valuationDetailData['market_price'] ?? null),
            'market_value_per_face_value' => $this->correctDecimal($valuationDetailData['market_value_per_face_value'] ?? null),
            'exchange_rate' => $valuationDetailData['exchange_rate'] ?? null,
            'valuation_factor' => $this->correctDecimal($valuationDetailData['valuation_factor'] ?? null),
            'accrued_interest' => $this->correctDecimal($valuationDetailData['accrued_interest'] ?? null),
        ]);
    }
    protected function storeSecurityDetail($data, $general_id)
    {
        $securityDetailData = $this->parseSecurityDetailSection($data);
        // return $securityDetailData;

        return MT569SecurityDetails::create([
            'm_t569_general_information_id' => $general_id,
            'isin' => $securityDetailData['isin'] ?? null,
            'xs' => $securityDetailData['xs'] ?? null,
            'security_description' => $securityDetailData['security_description'] ?? null,
            'face_amount' => $securityDetailData['face_amount'] ?? null,
            'safekeeping_account' => $securityDetailData['safekeeping_account'] ?? null,
            'denomination_currency' => $securityDetailData['denomination_currency'] ?? null,
            'market_price' => $securityDetailData['market_price'] ?? null,
            'rating_source' => $securityDetailData['rating_source'] ?? null,
            'rating_value' => $securityDetailData['rating_value'] ?? null,
        ]);
    }

    protected function storeFinanceSummary($data, $general_id)
    {
        $financial_data = $this->parseFinanceSummarySection($data);
        $financial_data['m_t569_general_information_id'] = $general_id;
        $financial_data['total_exposure_amount'] = $this->correctDecimal($financial_data['total_exposure_amount'] ?? null);
        $financial_data['total_collateral_reused'] = $this->correctDecimal($financial_data['total_collateral_reused'] ?? null);
        $financial_data['total_collateral_own'] = $this->correctDecimal($financial_data['total_collateral_own'] ?? null);
        $financial_data['margin'] = $this->correctDecimal($financial_data['margin'] ?? null);
        $financial_data['margin_amount'] = $this->correctDecimal($financial_data['margin_amount'] ?? null);
        $financial_data['collateral_value_held'] = $this->correctDecimal($financial_data['collateral_value_held'] ?? null);
        $financial_data['total_collateral_required'] = $this->correctDecimal($financial_data['total_collateral_required'] ?? null);
        return MT569FinancialSummaryDetails::create($financial_data);
    }

    protected function storeMt527ColleteralParties($data, $general_id)
    {
        $collateralPartyData = $this->parseMT527CollateralPartiesSection($data);
        $collateralPartyData['m_t527_general_id'] = $general_id;
        return MT527CollateralParty::create($collateralPartyData);
    }

    protected function storeMT527TransactionDetails($data, $general_id)
    {
        $transactionDetails = $this->parseMT527TransactionDetailsSection($data);
        $transactionDetails['m_t527_general_id'] = $general_id;
        return MT527TransactionDetails::create($transactionDetails);
    }

    protected function storeMT558TransactionDetails($data, $general_id)
    {
        $transactionDetailData = $this->parseMT558TransactionDetailsSection($data);
        $transactionDetailData['m_t558_general_id'] = $general_id;
        return MT558TransactionDetail::create($transactionDetailData);
    }

    protected function storeMT558LinkageData($data, $general_id)
    {
        $linkageData = $this->parseMT558LinkageSection($data);
        $linkageData['m_t558_general_id'] = $general_id;
        return MT558Linkage::create($linkageData);
    }

    protected function storeMT527General($data)
    {
        $financial_data = $this->parseMT527GenlSection($data);
        return MT527General::create($financial_data);
    }

    protected function storeMT558General($data)
    {
        $financial_data = $this->parseMT558GenlSection($data);
        return MT558General::create($financial_data);
    }
    protected function storeMT558StatusData($data, $general_id)
    {
        $statusData = $this->parseMT558StatusSection($data);
        $statusData['m_t558_general_id'] = $general_id;
        return MT558Status::create($statusData);
    }

    protected function storeMT558CollateralData($data, $general_id)
    {
        $collateralPartyData = $this->parseCollateralPartySection($data);
        $collateralPartyData['m_t558_general_id'] = $general_id;
        return MT558CollateralParty::create($collateralPartyData);
    }



    private function parseGenlSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:20C::SEME\/\/(.*)/', $line, $matches)) {
                $parsedData['msg_ref'] = $matches[1];
            } elseif (preg_match('/^:28E:(.*)/', $line, $matches)) {
                $parsedData['sequence_number'] = $matches[1];
            } elseif (preg_match('/^:13A::STAT\/\/(.*)/', $line, $matches)) {
                $parsedData['function_code'] = $matches[1];
            } elseif (preg_match('/^:22H::REPR\/\/(.*)/', $line, $matches)) {
                $parsedData['status'] = $matches[1];
            } elseif (preg_match('/^:23G:(.*)/', $line, $matches)) {
                $parsedData['is_part_of'] = $matches[1];
            } elseif (preg_match('/^:98C::PREP\/\/(.*)/', $line, $matches)) {
                $parsedData['prep_date'] = $matches[1];
            } elseif (preg_match('/^:22F::STBA\/\/(.*)/', $line, $matches)) {
                $parsedData['report_type'] = $matches[1];
            } elseif (preg_match('/^:22H::SFRE\/\/(.*)/', $line, $matches)) {
                $parsedData['safekeeping_reference'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT527GenlSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:20C::SEME\/\/(.*)/', $line, $matches)) {
                $parsedData['sender_reference'] = $matches[1];
            } elseif (preg_match('/^:28E:(.*)/', $line, $matches)) {
                $parsedData['sequence_number'] = $matches[1];
            } elseif (preg_match('/^:20C::SCTR\/\/(.*)/', $line, $matches)) {
                $parsedData['sender_collateral_reference'] = $matches[1];
            } elseif (preg_match('/^:20C::RCTR\/\/(.*)/', $line, $matches)) {
                $parsedData['receiver_collateral_reference'] = $matches[1];
            } elseif (preg_match('/^:20C::CLCI\/\/(.*)/', $line, $matches)) {
                $parsedData['client_collateral_reference'] = $matches[1];
            } elseif (preg_match('/^:20C::TRCIP\/\/(.*)/', $line, $matches)) {
                $parsedData['receiver_liquidity_reference'] = $matches[1];
            } elseif (preg_match('/^:23G(.*)/', $line, $matches)) {
                $parsedData['function_of_message'] = $matches[1];
            } elseif (preg_match('/^:22H::CINT\/\/(.*)/', $line, $matches)) {
                $parsedData['instruction_type_indicator'] = $matches[1];
            } elseif (preg_match('/^:22H::COLA\/\/(.*)/', $line, $matches)) {
                $parsedData['exposure_type_indicator'] = $matches[1];
            } elseif (preg_match('/^:22H::PEPR\/\/(.*)/', $line, $matches)) {
                $parsedData['client_indicator'] = $matches[1];
            } elseif (preg_match('/^:13B::ELIG\/\/(.*)/', $line, $matches)) {
                $parsedData['eligibility'] = $matches[1];
            } elseif (preg_match('/^:98A::EXPQ\/\/(.*)/', $line, $matches)) {
                $parsedData['execution_requested_date'] = $matches[1];
            } elseif (preg_match('/^:98A::SETT\/\/(.*)/', $line, $matches)) {
                $parsedData['settlement_date'] = $matches[1];
            } elseif (preg_match('/^:98A::PREP\/\/(.*)/', $line, $matches)) {
                $parsedData['preparation_date'] = $matches[1];
            } elseif (preg_match('/^:98A::TRAD\/\/(.*)/', $line, $matches)) {
                $parsedData['trade_date'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT558GenlSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:28E:(.*)/', $line, $matches)) {
                $parsedData['sequence_number'] = $matches[1];
            } elseif (preg_match('/^:20CA:(.*)/', $line, $matches)) {
                $parsedData['reference'] = $matches[1];
            } elseif (preg_match('/^:23G:(.*)/', $line, $matches)) {
                $parsedData['function_of_message'] = $matches[1];
            } elseif (preg_match('/^:98A::EXRQ\/\/(.*)/', $line, $matches)) {
                $parsedData['execution_request_date'] = $matches[1];
            } elseif (preg_match('/^:98A::SETT\/\/(.*)/', $line, $matches)) {
                $parsedData['settlement_date'] = $matches[1];
            } elseif (preg_match('/^:98A::PREP\/\/(.*)/', $line, $matches)) {
                $parsedData['prep_date'] = $matches[1];
            } elseif (preg_match('/^:(98A)::TRAD\/\/(.*)/', $line, $matches)) {
                $parsedData['trade_date'] = $matches[1];
            } elseif (preg_match('/^:22H::REPR\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_receive_provide_indicator'] = $matches[1];
            } elseif (preg_match('/^:13B::ELIG\/\/(.*)/', $line, $matches)) {
                $parsedData['eligibility'] = $matches[1];
            } elseif (preg_match('/^:22H::COLA\/\/(.*)/', $line, $matches)) {
                $parsedData['exposure_type_indicator'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT527CollateralPartiesSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:95A::PTYA\/\/(.*)/', $line, $matches)) {
                $parsedData['party_a'] = $matches[1];
            } elseif (preg_match('/^:95A::CLPA\/\/(.*)/', $line, $matches)) {
                $parsedData['party_a_client'] = $matches[1];
            } elseif (preg_match('/^:95A::PTYB\/\/(.*)/', $line, $matches)) {
                $parsedData['party_b'] = $matches[1];
            } elseif (preg_match('/^:95A::TRAG\/\/(.*)/', $line, $matches)) {
                $parsedData['triparty_agent'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT527TransactionDetailsSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:95A::PTYA\/\/(.*)/', $line, $matches)) {
                $parsedData['closing_date'] = $matches[1];
            } elseif (preg_match('/^:95A::CLPA\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_amount'] = $matches[1];
            } elseif (preg_match('/^:95A::PTYB\/\/(.*)/', $line, $matches)) {
                $parsedData['termination_transaction_amount'] = $matches[1];
            } elseif (preg_match('/^:95A::TRAG\/\/(.*)/', $line, $matches)) {
                $parsedData['pricing_rate'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT558TransactionDetailsSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:98A::TERM\/\/(.*)/', $line, $matches)) {
                $parsedData['closing_date'] = $matches[1];
            } elseif (preg_match('/^:19A::DEAL\/\/(.*)/', $line, $matches)) {
                $parsedData['deal_transaction_details'] = $matches[1];
            } elseif (preg_match('/^:22F::MICO\/\/(.*)/', $line, $matches)) {
                $parsedData['method_of_interest_computation'] = $matches[1];
            } elseif (preg_match('/^:19A::TRAG\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_amount'] = $matches[1];
            } elseif (preg_match('/^:95A::TRTE\/\/(.*)/', $line, $matches)) {
                $parsedData['termination_transaction_amount'] = $matches[1];
            } elseif (preg_match('/^:92A::PRIC\/\/(.*)/', $line, $matches)) {
                $parsedData['pricing_rate'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT558LinkageSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:20C::RELA\/\/(.*)/', $line, $matches)) {
                $parsedData['related_message_reference'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseMT558StatusSection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:25D::(.*)/', $line, $matches)) {
                $parsedData['status'] = $matches[1];
            } elseif (preg_match('/^:17B::CAPP\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_approved_flag'] = $matches[1];
            } elseif (preg_match('/^:17B::SAPP\/\/(.*)/', $line, $matches)) {
                $parsedData['settlement_approved_flag'] = $matches[1];
            } elseif (preg_match('/^:70E::CINS\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_instruction_narrative'] = $matches[1];
            } elseif (preg_match('/^:70D::REAS\/\/(.*)/', $line, $matches)) {
                $parsedData['reason_narrative'] = $matches[1];
            } elseif (preg_match('/^:19A::RMAG\/\/(.*)/', $line, $matches)) {
                $parsedData['required_margin_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::ALARM\/\/(.*)/', $line, $matches)) {
                $parsedData['collaterised_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::ESTT\/\/(.*)/', $line, $matches)) {
                $parsedData['settled_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::RALA\/\/(.*)/', $line, $matches)) {
                $parsedData['remaining_collaterised_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::RSTT\/\/(.*)/', $line, $matches)) {
                $parsedData['remaining_settlement_amount'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseSummarySection($data)
    {
        $parsedData = [];

        foreach ($data as $line) {
            if (preg_match('/^:19A::TEXA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_exposure'] = $matches[1];
            } elseif (preg_match('/^:19A::TCOR\/\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral'] = $matches[1];
            } elseif (preg_match('/^:19A::COVA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_covered'] = $matches[1];
            } elseif (preg_match('/^:19A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['margin'] = $matches[1];
            } elseif (preg_match('/^:98C::VALN\/\/(.*)/', $line, $matches)) {
                $parsedData['valuation_date'] = $matches[1];
            } elseif (preg_match('/^:19A::TVOC\/\/(.*)/', $line, $matches)) {
                $parsedData['total_valuation'] = $matches[1];
            }
        }

        return $parsedData;
    }
    private function parseSummaryCollateralSection($data)
    {
        $parsedData = [];

        foreach ($data as $line) {
            if (preg_match('/^:19A::TEXA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_exposure'] = $matches[1];
            } elseif (preg_match('/^:19A::TCOR\/\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral'] = $matches[1];
            } elseif (preg_match('/^:19A::COVA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_covered'] = $matches[1];
            } elseif (preg_match('/^:19A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['margin'] = $matches[1];
            } elseif (preg_match('/^:19A::TVOC\/\/(.*)/', $line, $matches)) {
                $parsedData['total_valuation'] = $matches[1];
            } elseif (preg_match('/^:92A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['valuation_margin'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseTransactionDetailSection($data)
    {
        $parsedData = [];

        // return $data;

        foreach ($data as $line) {
            if (preg_match('/^:20C::CLTR\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_transaction_ref'] = $matches[1];
            } elseif (preg_match('/^:20C::TCTR\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_count'] = $matches[1];
            } elseif (preg_match('/^:98A::EXRQ\/\/(.*)/', $line, $matches)) {
                $parsedData['settlement_date'] = $matches[1];
            } elseif (preg_match('/^:19A::TEXA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_exposure_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::TCOR\/\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral_required'] = $matches[1];
            } elseif (preg_match('/^:19A::COVA\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_value'] = $matches[1];
            } elseif (preg_match('/^:19A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['margin_amount'] = $matches[1];
            } elseif (preg_match('/^:20C::TRAG\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_collateral_ref'] = $matches[1];
            } elseif (preg_match('/^:19A::TVOC\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_collateral_amount'] = $matches[1];
            } elseif (preg_match('/^:19A::TCFA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_cash_flow'] = $matches[1];
            } elseif (preg_match('/^:92A::PRIC\/\/(.*)/', $line, $matches)) {
                $parsedData['price'] = $matches[1];
            } elseif (preg_match('/^:92A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['valuation_margin'] = $matches[1];
            } elseif (preg_match('/^:25D::TREX\/\/(.*)/', $line, $matches)) {
                $parsedData['transaction_extension'] = $matches[1];
            } elseif (preg_match('/^:98B::TERM\/\/(.*)/', $line, $matches)) {
                $parsedData['term_type'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseValuationDetailSection($data)
    {
        $parsedData = [];

        foreach ($data as $line) {
            if (preg_match('/^:17B::COLL\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_flag'] = $matches[1];
            } elseif (preg_match('/^:17B::SECU\/\/(.*)/', $line, $matches)) {
                $parsedData['security_flag'] = $matches[1];
            } elseif (preg_match('/^:98C::SETT\/\/(.*)/', $line, $matches)) {
                $parsedData['settlement_date'] = $matches[1];
            } elseif (preg_match('/^:19A::MKTP\/\/(.*)/', $line, $matches)) {
                $parsedData['market_price'] = $matches[1];
            } elseif (preg_match('/^:19A::ACRU\/\/(.*)/', $line, $matches)) {
                $parsedData['accrued_interest'] = $matches[1];
            } elseif (preg_match('/^:19A::MVPF\/\/(.*)/', $line, $matches)) {
                $parsedData['market_value_per_face_value'] = $matches[1];
            } elseif (preg_match('/^:92B::EXCH\/\/(.*)/', $line, $matches)) {
                $parsedData['exchange_rate'] = $matches[1];
            } elseif (preg_match('/^:92A::VAFC\/\/(.*)/', $line, $matches)) {
                $parsedData['valuation_factor'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseSecurityDetailSection($data)
    {
        $parsedData = [];

        // return $data;

        foreach ($data as $line) {
            if (preg_match('/^:35B:(.*)/', $line, $matches)) {
                $parsedData['isin'] = $matches[1];
            } elseif (preg_match('/\/XS\/(.*)/', $line, $matches)) {
                $parsedData['xs'] = $matches[1];
            } elseif (preg_match('/CAD\ (.*)/', $line, $matches)) {
                $parsedData['security_description'] = $matches[1];
            } elseif (preg_match('/^:36B::SECV\/\/(.*)/', $line, $matches)) {
                $parsedData['face_amount'] = $matches[1];
            } elseif (preg_match('/^:97A::SAFE\/\/(.*)/', $line, $matches)) {
                $parsedData['safekeeping_account'] = $matches[1];
            } elseif (preg_match('/^:11A::DENO\/\/(.*)/', $line, $matches)) {
                $parsedData['denomination_currency'] = $matches[1];
            } elseif (preg_match('/^:90A::MRKT\/(.*)/', $line, $matches)) {
                $parsedData['market_price'] = $matches[1];
            } elseif (preg_match('/^:94B::RATS\/(.*)/', $line, $matches)) {
                $parsedData['rating_source'] = $matches[1];
            } elseif (preg_match('/^:70C::RATS\/\/(.*)/', $line, $matches)) {
                $parsedData['rating_value'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseFinanceSummarySection($data)
    {
        $parsedData = [];

        // return $data;

        foreach ($data as $line) {
            if (preg_match('/^:13B::ELIG\/\/(.*)/', $line, $matches)) {
                $parsedData['eligibility'] = $matches[1];
            } elseif (preg_match('/^:95R::PTYA\/(.*)/', $line, $matches)) {
                $parsedData['party_b'] = $matches[1];
            } elseif (preg_match('/^:95R::TRAG\/(.*)/', $line, $matches)) {
                $parsedData['triparty_agent'] = $matches[1];
            } elseif (preg_match('/^:19A::TCOR\/\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral_required'] = $matches[1];
            } elseif (preg_match('/^:19A::COVA\/\/(.*)/', $line, $matches)) {
                $parsedData['collateral_value_held'] = $matches[1];
            } elseif (preg_match('/^:19A::MARG\/\/(.*)/', $line, $matches)) {
                $parsedData['margin_amount'] = $matches[1];
            } elseif (preg_match('/^:92A::MARG\/(.*)/', $line, $matches)) {
                $parsedData['margin'] = $matches[1];
            } elseif (preg_match('/^:19A::TVOC\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral_own'] = $matches[1];
            } elseif (preg_match('/^:19A::TVRC\/\/(.*)/', $line, $matches)) {
                $parsedData['total_collateral_reused'] = $matches[1];
            } elseif (preg_match('/^:19A::TEXA\/\/(.*)/', $line, $matches)) {
                $parsedData['total_exposure_amount'] = $matches[1];
            }
        }

        return $parsedData;
    }

    private function parseCollateralPartySection($data)
    {
        $parsedData = [];
        // return $data;
        foreach ($data as $line) {
            if (preg_match('/^:95R::PTYA\/(.*)/', $line, $matches)) {
                $parsedData['party_id'] = $matches[1];
                $parsedData['party'] = $matches[1];
            }
        }
        return $parsedData;
    }

    private function correctDecimal($value)
    {
        return str_replace(",", ".", substr($value, 3));
    }
}
