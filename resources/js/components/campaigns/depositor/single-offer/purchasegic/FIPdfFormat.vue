<template>
    <vue-html2pdf @beforeDownload="beforeDownload" :show-layout="false" :float-layout="true" :enable-download="false"
        :preview-modal="false" :paginate-elements-by-height="1400" :filename="filename" :pdf-quality="2"
        :manual-pagination="true" :margin="[20,10,20,10]" pdf-format="a4" pdf-orientation="portrait" pdf-content-width="800px" ref="html2Pdf">
        <!-- <section slot="pdf-content" ref="myfidata"> -->
        <div slot="pdf-content" style="width:794px !important"
            class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <div class="row">
                <template class="pdf-item">
                    <div class="col-md-12 ">
                        <div class="d-flex justify-ontent-center w-100">
                            <img src="/assets/images/yie-black.svg"
                                style="max-height: 200px; width:500px; margin: 20px auto;" alt="" srcset="">
                        </div>
                        <p class="title">Financial Institution Investment application</p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Organization Information
                        </p>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Organization Name</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput class="flex-grow-1" id="orgname" placeholder="Not Provided"
                                    input_type="text" v-model="organizationName" :currentValue='organizationName' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Organization Type</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="orgtype" placeholder="Not Provided" input_type="text"
                                    v-model="incorporationType" :currentValue='incorporationType' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Trade Name / Doing Business As</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="tradename" placeholder="Not Provided" input_type="text"
                                    v-model="tradeName" :currentValue='tradeName' />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class="pdf-input-label">
                                <p class="p-0 m-0">Incorporation Number</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="incnumber" placeholder="Not Provided" input_type="text"
                                    v-model="incorporationNumber" :currentValue='incorporationNumber' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">CRA Business Number</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    v-model="craNumber" :currentValue='craNumber' />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-20">

                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Date of Incorporation</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    :currentValue='formatDateToCustomFormat(incorporationDate)' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Industry</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    :currentValue='industry' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Province of Incorporation</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    v-model="provinceOfIncorporation" :currentValue='provinceOfIncorporation' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Intended Use of Account</p>
                            </div>
                            <div class="d-flex justify-content-start  flex-nowrap gap-2">
                                <div class="item-card-holder text-capitalize flex-grow-1"
                                    v-for="(iuse, index) in intendedUseOfAccount" :key="index">
                                    <p class="p-0 m-0 new-p">{{ iuse }}</p>
                                </div>
                                <!-- <div class="item-card-holder">Home Alone</div> -->
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12  mb-3">
                        <label for="" class="textarea-label">
                            <p class="p-0 m-0">Company Description</p>
                        </label>
                        <textarea class="textarea w-100" :class="{ 'textarea-error': companyDescError }"
                            placeholder="Not provided" v-model="companyDesc" name="" id="" cols=""
                            rows="5">{{ companyDesc }}</textarea>
                    </div>
                </template>

                <template class="pdf-item">

                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Registered organization address
                        </p>
                    </div>

                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Phone Number</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    :currentValue='telephone' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Email</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    :currentValue='email' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-20">
                        <div class="d-flex gap-2 flex-row w-100">
                            <div class=" pdf-input-label">
                                <p class="p-0 m-0">Website</p>
                            </div>
                            <div class="flex-grow-1">
                                <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                    v-model="website" :currentValue='website' />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-20">
                        <PDFFormInput label="Street Address" id="cranumber" placeholder="Not Provided" input_type="text"
                            v-model="address1.streetAddress" :currentValue='address1.streetAddress' />

                    </div>



                    <template v-if="!useMailingAddressOne">
                        <div class="col-md-12 py-1 mt-2">
                            <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                                <div class="d-flex flex-row address-subheading gap-2">
                                    <span>
                                        <p class="p-0 m-0 new-p"> Mailing Address</p>
                                    </span>

                                    <span style=" color: #5063F4;" class="d-flex gap-2 selected">
                                        <p class="p-0 m-0 new-p"> (If different from business address)</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-20">
                            <PDFFormInput label="Street Address" id="cranumber" placeholder="Not Provided"
                                input_type="text" :currentValue='mailing.streetAddress' />

                        </div>


                    </template>

                </template>
                <div class="html2pdf__page-break" />
                <template class="pdf-item">

                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Individuals authorized to bind the organization
                        </p>
                    </div>
                    <template v-for="(individual, index) in keyindividuals">
                        <template v-if="individual.orperating_for_entity">

                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>

                        </template>
                    </template>
                </template>

                <template class="pdf-item" v-if="hasDirector">
                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            List of Directors
                        </p>
                    </div>
                    <template v-for="(individual, index) in keyindividuals">
                        <template v-if="individual.is_director">
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>
                            <br>
                        </template>
                    </template>
                </template>
                <template class="pdf-item">

                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Beneficial Ownership (Individual)
                        </p>
                    </div>
                    <template v-for="(individual, index) in keyindividuals" v-if="individual.owns_over_twenty_five">
                        <div class="col-md-12 py-1 mt-2">
                            <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                                <div class="d-flex flex-row address-subheading gap-2">
                                    <span> Individual {{ index + 1 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">
                                    <p class="p-0 m-0">Person acting on their own behalf or through POA </p>
                                </div>
                                <div class="flex-grow-1">
                                    <input :checked="individual.is_actingonattorneypower" class="my-checkbox"
                                        type="checkbox" name="address">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">
                                    <p class="p-0 m-0"> Agent acting on behalf of corporation or entity</p>
                                </div>
                                <div class="flex-grow-1">
                                    <input :checked="individual.operating_for_corporation" class="my-checkbox"
                                        type="checkbox">
                                </div>
                            </div>
                        </div>
                        <template>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row ">
                                <div class=" pdf-input-label">
                                    <p class="p-0 m-0">Percentage Owenership</p>
                                </div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                        :currentValue='individual.percentage_ownership ? `${individual.percentage_ownership}%` : ""' />
                                </div>
                            </div>
                        </div>

                    </template>
                </template>
                <template class="pdf-item" v-if="entities !=null && entities.some(element => element.owns_over_twenty_five)">

                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Beneficial Ownership (Entity)
                        </p>
                    </div>
                    <template v-for="(entity, index) in entities">
                        <template v-if="entity.owns_over_twenty_five">
                            <div class="col-md-12 py-1 mt-2">
                                <div
                                    class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                                    <div class="d-flex flex-row address-subheading gap-2">
                                        <span> Entity {{ index + 1 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Entity Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue="entity.organization_name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Incorporation Number</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='entity.inc_business_number' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Percentage Ownership</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='entity.percentage_ownership ? `${entity.percentage_ownership}%` : ""' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Incorporation Type</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='getOrgType(entity.organization_type)' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Province of Incorporation</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='getExactProvince(entity.incorporation_province)' />
                                    </div>
                                </div>
                            </div>
                            <br>

                        </template>
                    </template>
                </template>
                <div v-if="entities !=null && entities.some(element => element.owns_over_twenty_five)" class="html2pdf__page-break" />

                <template class="pdf-item">
                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Authorized Agents
                        </p>
                    </div>
                    <template v-for="(individual, index) in keyindividuals">
                        <template v-if="individual.operating_for_corporation">
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>
                            <br>
                        </template>
                    </template>
                </template>
                <template class="pdf-item">
                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Third Party Declaration
                        </p>
                    </div>
                    <p class="third-party-declaration">A third party is another entity, or an individual or
                        organization
                        not named as a Director,Officer, Signing Authority, or Owner, who may from time instructs
                        your
                        organization to time give directions conduct transactions pertaining to the monies to be
                        held in
                        this account on their behalf</p>

                    <div class="col-md-12 py-1 mt-2">
                        <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                            <div class="d-flex flex-row address-subheading gap-2">
                                <span> Individual</span>
                            </div>
                        </div>
                    </div>
                    <template v-for="(individual, index) in keyindividuals">
                        <template
                            v-if="!(individual.is_director || individual.is_signingofficer) && individual.operating_for_corporation">
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Nature of relationship with the third party</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.relationship_with_corporation' />
                                    </div>
                                </div>
                            </div>
                            <br>
                        </template>
                    </template>
                    <div class="col-md-12 py-1 mt-2">
                        <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                            <div class="d-flex flex-row address-subheading gap-2">
                                <span> Entity</span>
                            </div>
                        </div>
                    </div>
                    <template v-for="(entity, index) in entities">
                        <template v-if="entity.orperating_for_entity">
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Legal Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='entity.organization_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Incorporation Number</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='entity.inc_business_number' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Province of Incorporation</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='getExactProvince(entity.incorporation_province)' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Organization type </p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='getOrgType(entity.organization_type)' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Nature of relationship with the third party</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='entity.relationship_with_entity' />
                                    </div>
                                </div>
                            </div>

                        </template>
                    </template>
                </template>
                <div v-if="entities !=null && entities.some(element => element.owns_over_twenty_five)" class="html2pdf__page-break" />

                <template class="pdf-item">
                    <div class="col-md-12 mt-3">
                        <p class="section-header-pdf">
                            Signing Officer Information
                        </p>
                    </div>
                    <template v-for="(individual, index) in keyindividuals">
                        <template v-if="individual.is_signingofficer">

                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Job Title</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.job_title' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Last Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Fisrt Name</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" placeholder="Not Provided" input_type="text"
                                            :currentValue='individual.first_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Phone Number</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" input_type="text" :currentValue='telephone' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="d-flex gap-2 flex-row w-100">
                                    <div class=" pdf-input-label">
                                        <p class="p-0 m-0">Email Address</p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <PDFFormInput id="cranumber" input_type="text" :currentValue='email' />
                                    </div>
                                </div>
                            </div>

                        </template>
                    </template>
                </template>
                <div class="pdf-item d-none">
                    <div class="col-md-12 py-1 mt-2">
                        <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                            <div class="d-flex flex-row address-subheading gap-2">
                                <span> Home Address</span>
                            </div>
                        </div>
                    </div>
                    <template>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">Apt/Unit No</div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">Street Address</div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">City</div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="form-group row">
                                <label for="cranumber" class="col-sm-4 col-form-label pdf-input-label">Province</label>
                                <div class="col-sm-8">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">Country</div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="d-flex gap-2 flex-row w-100">
                                <div class=" pdf-input-label">Postal Code</div>
                                <div class="flex-grow-1">
                                    <PDFFormInput id="cranumber" input_type="text" v-model="craNumber"
                                        :currentValue='craNumber' />
                                </div>
                            </div>
                        </div>

                    </template>
                </div>

            </div>
        </div>
    </vue-html2pdf>
</template>

<script>
import PDFFormInput from './shared/PDFFormInput.vue';
import CustomSelectInput from './shared/CustomSelectInput.vue';
import CustomMultiSelect from './shared/CustomMultiSelect.vue';
import CustomDateInput from './shared/CustomDateInput.vue'
import VueHtml2pdf from 'vue-html2pdf'
import html2pdf from 'html2pdf.js';



export default {
    props: ['user', 'organization_id', 'user'],
    components: {
        PDFFormInput, CustomSelectInput, VueHtml2pdf
    },
    mounted() {
        this.getAllIndustries()
        this.getAllProvinces()

        this.getUserBusinessOrganization()

        if (this.user) {
            this.getUser()
        }
    },
    data() {
        return {
            filename: "mike",
            places: null,
            uploadimage: false,
            uploaded_image: null,
            hasDirector: false,
            twentyfiveOwnership: false,
            provinces: null,
            industries: null,
            maxCount: 300,
            currentCount: 0,
            countError: null,
            requiredChecker: false,
            organizationName: null,
            incorporationType: null,
            tradeName: null,
            industry: null,
            mailingerror: false,
            address1error: false,
            incorporationNumber: null,
            craNumber: null,
            incorporationDate: null,
            provinceOfIncorporation: null,
            website: null,
            companyDesc: null,
            intendedUseOfAccount: null,
            keyindividuals: null,
            entities: null,
            address1: {
                streetAddress: null,
                city: null,
                province: null,
                postalCode: null
            },
            mailing: {
                streetAddress: null,
                city: null,
                province: null,
                postalCode: null
            },
            textareamessage: '',
            useMailingAddressOne: true,
            // define error variables
            organizationNameError: false,
            tradeNameError: false,
            incorporationNumberError: false,
            craNumberError: false,
            websiteError: false,
            companyDescError: false,
            useRegAddressForMailingAdress: null,
            fail: false,
            pdfContent: null,
            failmessage: 'We are unable to update your data, please try again or contact us at info',
            organizationtypes: [
                {
                    id: "CORPORATION",
                    name: "Incorporation(Corporation)"
                },
                {
                    id: "SOLE",
                    name: "Sole Proprietorship"
                },
                {
                    id: "CROWN",
                    name: "Crown Organization"
                },
                {
                    id: "PARTNERSHIP",
                    name: "Partnership"
                }

            ],
            generatedpdf: null,
            email: null,
            telephone: null
        }
    },
    methods: {
        getUser() {
            let user = this.user

            this.email = user.email
            this.telephone = user.demographic_data.phone
        },
        getOrgType(orga) {
            let organization = null
            this.organizationtypes.forEach(element => {
                if (element.id == orga) {
                    organization = element.name
                }
            })
            return organization
        },
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },

        getExactProvince(value) {
            let province = null
            this.provinces.forEach(element => {
                if (element.id == value) {
                    province = element.province_name
                }
            })
            return province

        },
        async beforeDownload({ html2pdf, options, pdfContent }) {
            const pdfbase64 = await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
                const totalPages = pdf.internal.getNumberOfPages()
                for (let i = 1; i <= totalPages; i++) {
                    pdf.setPage(i)
                    pdf.setFontSize(10)
                    pdf.setTextColor(150)
                    pdf.text('Page ' + i + ' of ' + totalPages, (pdf.internal.pageSize.getWidth() * 0.88), (pdf.internal.pageSize.getHeight() - 0.3))
                }
                return pdf.output('datauristring');
            })
            this.generatedpdf = pdfbase64
            this.$emit('mypdf', this.generatedpdf)
        },
        downloadPdf() {

            this.$refs.html2Pdf.generatePdf()

        },
        handlePDF(pdf) {

            console.log(pdf);
            this.pdfContentVariable = pdf.output('bloburl');
        },
        async getUserBusinessOrganization() {

            await axios.get(`/organization-data/${this.organization_id}`).then(response => {
                let returndata = response.data.data
                this.organizationName = returndata.name
                this.incorporationType = this.getOrgType(returndata.registration_type)

                this.provinceOfIncorporation = this.getExactProvince(returndata.province_of_incorporation)

                this.industries.forEach(element => {
                    if (element.id == returndata.industry_id) {
                        this.industry = element.name.toLowerCase()
                    }
                })
                // this.incorporationType = returndata.registration_type
                this.tradeName = returndata.trade_name
                this.incorporationNumber = returndata.incoporation_number
                this.craNumber = returndata.CRA_business_number
                this.incorporationDate = returndata.incoporation_date
                this.website = returndata.demographic_data.website
                this.companyDesc = returndata.demographic_data.description
                this.intendedUseOfAccount = returndata.intended_use
                this.address1.postalCode = returndata.demographic_data.postal_code
                this.address1.streetAddress = returndata.demographic_data.address1
                this.mailing.streetAddress = returndata.demographic_data.address2
                this.keyindividuals = returndata.key_individuals
                this.entities = returndata.entities
                this.hasDirector = this.keyindividuals.some(element => element.is_director);
                if (this.address1.streetAddress == this.mailing.streetAddress && this.mailing.streetAddress != null) {
                    this.useMailingAddressOne = true
                } else {
                    this.useMailingAddressOne = false
                }
                this.twentyfiveOwnership = this.keyindividuals.some(element => element.owns_over_twenty_five);

            }).catch(err => {
                // console.log(err)
            })
        },
        getAllIndustries() {
            axios.get('/get-all-industries').then(response => {
                // console.log(response.data)
                this.industries = response.data
            }).catch(err => {
                // console.log(err)
            })
        },
        getAllProvinces() {
            axios.get('/get-all-provinces').then(response => {
                // console.log(response.data)
                this.provinces = response.data
            }).catch(err => {
                // console.log(err)
            })
        },


        getIntent(value) {
            // console.log(value),
            this.intendedUseOfAccount = value
        },



    },

}
</script>


<style>
.new-p {

    font-family: Montserrat !important;
}

.third-party-declaration {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 18px;
    /* 112.5% */
}

.item-card-holder {
    display: flex;
    padding: 8px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    gap: 4px;
    border-radius: 8px;
    background: var(--Yield-Exchange-Colors-Yield-Exchange-Light-Purple, #EFF2FE);
    color: var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4);

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.top-title {
    margin-bottom: 30px;
}

.mb-20 {
    margin-bottom: 20px !important;
}

.selected-checkbox-action {
    color: #5063F4 !important;
}

.not-selected-checkbox-action {
    color: #252525 !important;
}

.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;

    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 500;
    line-height: 150%;
    /* text-transform: capitalize; */
}

.textarea-label {
    color: #252525;
    font-family: Montserrat !important;
    ;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;

}

.textarea-error {
    border: 1px solid #FF0101 !important;
}

.top-title p {
    color: #252525;

    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */

}

.logo_upload_title {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Darks, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-decoration-line: underline;
    text-transform: capitalize;
    padding: 0;
    margin: 0;
}

.logo_upload_desc {
    padding: 0;
    font-weight: 400;
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}


.section-header-pdf {
    color: #252525;
    /* font-feature-settings: 'clig' off, 'liga' off; */
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 108.333% */
    text-transform: capitalize;
    background: #EFF2FE;
    padding: 10px;
}

.address-subheading>span {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;

}

.notes-subheading span div {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;

}


input[type=checkbox] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=checkbox]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=checkbox]:checked::before {
    transform: scale(1);
}

input[type=checkbox]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}

.pdf-input-label {
    display: flex !important;
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat !important;
    font-size: 15px;
    font-weight: 300;
    line-height: normal;
    font-style: normal;
    /* word-wrap: break-word; */
    align-items: center
}

.pdf-input-label p {
    /* display: flex !important; */
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat !important;
    font-size: 15px;
    font-weight: 300;
    line-height: normal;
    font-style: normal;
    /* word-wrap: break-word; */
    /* align-items: center */
}
</style>