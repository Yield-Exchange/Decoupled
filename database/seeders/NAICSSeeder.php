<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NAICSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //https://dataweb.usitc.gov/classification/commodity-description/NAIC/3
        $banks=[];
//            [
//                'description'=>'AGRICULTURAL PRODUCTS',
//                'code'=>1111,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'LIVESTOCK & LIVESTOCK PRODUCTS',
//                'code'=>1112,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'FORESTRY PRODUCTS, NESOI',
//                'code'=>113,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'FISH, FRESH/CHILLED/FROZEN & OTHER MARINE PRODUCTS',
//                'code'=>114,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'OIL & GAS',
//                'code'=>211,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'MINERALS & ORES',
//                'code'=>212,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'FOOD & KINDRED PRODUCTS',
//                'code'=>311,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'BEVERAGES & TOBACCO PRODUCTS',
//                'code'=>312,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'TEXTILES & FABRICS',
//                'code'=>313,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'TEXTILE MILL PRODUCTS',
//                'code'=>314,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'APPAREL & ACCESSORIES',
//                'code'=>315,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'LEATHER & ALLIED PRODUCTS',
//                'code'=>316,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'WOOD PRODUCTS',
//                'code'=>321,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PAPER',
//                'code'=>322,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PRINTED MATTER AND RELATED PRODUCTS, NESOI',
//                'code'=>323,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PETROLEUM & COAL PRODUCTS',
//                'code'=>324,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'CHEMICALS',
//                'code'=>325,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PLASTICS & RUBBER PRODUCTS',
//                'code'=>326,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'NONMETALLIC MINERAL PRODUCTS',
//                'code'=>327,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PRIMARY METAL MFG',
//                'code'=>331,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'FABRICATED METAL PRODUCTS, NESOI',
//                'code'=>332,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'MACHINERY, EXCEPT ELECTRICAL',
//                'code'=>333,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'COMPUTER & ELECTRONIC PRODUCTS',
//                'code'=>334,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'ELECTRICAL EQUIPMENT, APPLIANCES & COMPONENTS',
//                'code'=>335,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'TRANSPORTATION EQUIPMENT',
//                'code'=>336,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'FURNITURE & FIXTURES',
//                'code'=>337,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'MISCELLANEOUS MANUFACTURED COMMODITIES',
//                'code'=>339,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'NEWSPAPERS, BOOKS & OTHER PUBLISHED MATTER, NESOI',
//                'code'=>511,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'PUBLISHED PRINTED MUSIC AND MUSIC MANUSCR',
//                'code'=>512,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'WASTE AND SCRAP',
//                'code'=>910,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'USED OR SECOND-HAND MERCHANDISE',
//                'code'=>920,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'USED OR SECOND-HAND MERCHANDISE',
//                'code'=>930,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'GOODS RETURNED (EXPORTS FOR CANADA ONLY)',
//                'code'=>980,
//                'type'=>'DEPOSITOR',
//            ],
//            [
//                'description'=>'OTHER SPECIAL CLASSIFICATION PROVISIONS',
//                'code'=>990,
//                'type'=>'DEPOSITOR',
//            ]
//        ];

        //https://dataweb.usitc.gov/classification/commodity-description/NAIC/4
        if(true) {
            return;
        }
        $depositors =[
            [
                'description'=>'OILSEEDS & GRAINS',
                'code'=>1111,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'VEGETABLES & MELONS',
                'code'=>1112,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FRUITS & TREE NUTS',
                'code'=>1113,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MUSHROOMS, NURSERY & RELATED PRODUCTS',
                'code'=>1114,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER AGRICULTURAL PRODUCTS',
                'code'=>1119,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CATTLE',
                'code'=>1121,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SWINE',
                'code'=>1122,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'POULTRY & EGGS',
                'code'=>1123,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SHEEP, GOATS & FINE ANIMAL HAIR',
                'code'=>1124,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FARMED FISH AND RELATED PRODUCTS',
                'code'=>1125,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER ANIMALS',
                'code'=>1129,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FORESTRY PRODUCTS',
                'code'=>1132,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'TIMBER & LOGS',
                'code'=>1133,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FISH, FRESH/CHILLED/FROZEN & OTHER MARINE PRODUCTS',
                'code'=>1141,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OIL & GAS',
                'code'=>2111,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'COAL & PETROLEUM GASES',
                'code'=>2121,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'METAL ORES',
                'code'=>2122,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'NONMETALLIC MINERALS',
                'code'=>2123,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'ANIMAL FOODS',
                'code'=>3111,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'GRAIN & OILSEED MILLING PRODUCTS',
                'code'=>3112,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SUGAR & CONFECTIONERY PRODUCTS',
                'code'=>3113,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FRUITS & VEG PRESERVES & SPECIALTY FOODS',
                'code'=>3114,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'DAIRY PRODUCTS',
                'code'=>3115,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MEAT PRODUCTS & MEAT PACKAGING PRODUCTS',
                'code'=>3116,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SEAFOOD PRODS, PREPARED, CANNED & PACKAGED',
                'code'=>3117,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'BAKERY & TORTILLA PRODUCTS',
                'code'=>3118,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FOODS, NESOI',
                'code'=>3119,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'BEVERAGES',
                'code'=>3121,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'TOBACCO PRODUCTS',
                'code'=>3122,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FIBERS, YARNS & THREADS',
                'code'=>3131,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'FABRICS',
                'code'=>3132,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FINISHED & COATED TEXTILE FABRICS',
                'code'=>3133,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'TEXTILE FURNISHINGS',
                'code'=>3141,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER TEXTILE PRODUCTS',
                'code'=>3149,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'KNIT APPAREL',
                'code'=>3151,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'APPAREL',
                'code'=>3152,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'APPAREL ACCESSORIES',
                'code'=>3159,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'LEATHER & HIDE TANNING',
                'code'=>3161,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FOOTWEAR',
                'code'=>3162,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER LEATHER PRODUCTS',
                'code'=>3169,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SAWMILL & WOOD PRODUCTS',
                'code'=>3211,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'VENEER, PLYWOOD & ENGINEERED WOOD PRODUCTS',
                'code'=>3212,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER WOOD PRODUCTS',
                'code'=>3219,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'PULP, PAPER & PAPERBOARD MILL PRODUCTS',
                'code'=>3221,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CONVERTED PAPER PRODUCTS',
                'code'=>3222,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PRINTED MATTER AND RELATED PRODUCTS, NESOI',
                'code'=>3231,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PETROLEUM & COAL PRODUCTS',
                'code'=>3241,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'BASIC CHEMICALS',
                'code'=>3251,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'RESIN, SYN RUBBER, ARTF & SYN FIBERS/FIL',
                'code'=>3252,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PESTICIDES, FERTILIZERS & OTH AGRI CHEMICALS',
                'code'=>3253,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PHARMACEUTICALS & MEDICINES',
                'code'=>3254,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PAINTS, COATINGS & ADHESIVES',
                'code'=>3255,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SOAPS, CLEANING COMPOUNDS & TOILET PREPARATIONS',
                'code'=>3256,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER CHEMICAL PRODUCTS & PREPARATIONS',
                'code'=>3259,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PLASTICS PRODUCTS',
                'code'=>3261,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'RUBBER PRODUCTS',
                'code'=>3262,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CLAY & REFRACTORY PRODUCTS',
                'code'=>3271,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'GLASS & GLASS PRODUCTS',
                'code'=>3272,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CEMENT & CONCRETE PRODUCTS',
                'code'=>3273,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'LIME & GYPSUM PRODUCTS',
                'code'=>3261,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER NONMETALLIC MINERAL PRODUCTS',
                'code'=>3279,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'IRON & STEEL & FERROALLOY',
                'code'=>3311,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'STEEL PRODUCTS FROM PURCHASED STEEL',
                'code'=>3312,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ALUMINA & ALUMINUM & PROCESSING',
                'code'=>3313,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'NONFERROUS (EXC ALUM) & PROCESSING',
                'code'=>3314,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FOUNDRIES',
                'code'=>3315,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CROWNS/CLOSURES/SEALS & OTHER PACKING ACCESSORIES',
                'code'=>3321,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'CUTLERY & HANDTOOLS',
                'code'=>3322,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ARCHITECTURAL & STRUCTURAL METALS',
                'code'=>3323,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'BOILERS, TANKS & SHIPPING CONTAINERS',
                'code'=>3324,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'HARDWARE',
                'code'=>3325,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SPRINGS & WIRE PRODUCTS',
                'code'=>3326,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'BOLTS/NUTS/SCRWS/RIVTS/WASHRS & OTHER TURNED PRODS',
                'code'=>3327,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER FABRICATED METAL PRODUCTS',
                'code'=>3329,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'AG & CONSTRUCTION & MACHINERY',
                'code'=>3323,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ARCHITECTURAL & STRUCTURAL METALS',
                'code'=>3331,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'INDUSTRIAL MACHINERY',
                'code'=>3332,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'COMMERCIAL & SERVICE INDUSTRY MACHINERY',
                'code'=>3323,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'HVAC & COMMERCIAL REFRIGERATION EQUIPMENT',
                'code'=>3334,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'METALWORKING MACHINERY',
                'code'=>3335,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ENGINES, TURBINES & POWER TRANSMSN EQUIP',
                'code'=>3336,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER GENERAL PURPOSE MACHINERY',
                'code'=>3339,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'COMPUTER EQUIPMENT',
                'code'=>3341,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'COMMUNICATIONS EQUIPMENT',
                'code'=>3342,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'AUDIO & VIDEO EQUIPMENT',
                'code'=>3343,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SEMICONDUCTORS & OTHER ELECTRONIC COMPONENTS',
                'code'=>3344,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'NAVIGATIONAL/MEASURING/MEDICAL/CONTROL INSTRUMENT',
                'code'=>3345,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MAGNETIC & OPTICAL MEDIA',
                'code'=>3346,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ELECTRIC LIGHTING EQUIPMENT',
                'code'=>3351,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'HOUSEHOLD APPLIANCES AND MISC MACHINES, NESOI',
                'code'=>3352,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ELECTRICAL EQUIPMENT',
                'code'=>3353,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'ELECTRICAL EQUIPMENT & COMPONENTS, NESOI',
                'code'=>3359,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MOTOR VEHICLES',
                'code'=>3361,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MOTOR VEHICLE BODIES & TRAILERS',
                'code'=>3362,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'MOTOR VEHICLE PARTS',
                'code'=>3362,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'AEROSPACE PRODUCTS & PARTS',
                'code'=>3364,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'RAILROAD ROLLING STOCK',
                'code'=>3365,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SHIPS & BOATS',
                'code'=>3366,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'TRANSPORTATION EQUIPMENT, NESOI',
                'code'=>3369,
                'type'=>'DEPOSITOR',
            ],

            [
                'description'=>'HOUSEHOLD & INSTITUTIONAL FURN & KITCHEN CABINETS',
                'code'=>3371,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OFFICE FURNITURE (INCLUDING FIXTURES)',
                'code'=>3372,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'FURNITURE RELATED PRODUCTS, NESOI',
                'code'=>3379,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MEDICAL EQUIPMENT & SUPPLIES',
                'code'=>3391,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MISCELLANEOUS MANUFACTURED COMMODITIES',
                'code'=>3399,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'NEWSPAPERS, BOOKS & OTHER PUBLISHED MATTER, NESOI',
                'code'=>5111,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'SOFTWARE, NESOI',
                'code'=>5112,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'MOTOR VEHICLE BODIES & TRAILERS',
                'code'=>3362,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'PUBLISHED PRINTED MUSIC AND MUSIC MANUSCRIPTS',
                'code'=>5122,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'WASTE AND SCRAP',
                'code'=>9100,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'USED OR SECOND-HAND MERCHANDISE',
                'code'=>9200,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'USED OR SECOND-HAND MERCHANDISE',
                'code'=>9300,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'GOODS RETURNED (EXPORTS FOR CANADA ONLY)',
                'code'=>9800,
                'type'=>'DEPOSITOR',
            ],
            [
                'description'=>'OTHER SPECIAL CLASSIFICATION PROVISIONS',
                'code'=>9900,
                'type'=>'DEPOSITOR',
            ]

        ];

        DB::table('naics_codes')->truncate();
        $data = array_merge($banks,$depositors);
        foreach ($data as $datum) {
//            if(  !DB::table('naics_codes')->where('code','=',$datum['code'])->first() ) {
                DB::table('naics_codes')->insert($datum);
//            }
        }
    }
}
