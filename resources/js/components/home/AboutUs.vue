<template>
    <div>
        <div class="col-md-12">
            <div class="card card-shadow card-center" id="card">
                <div class="card-body" id="card-body">
                    <h3>About us</h3>
                    <p>Yield Exchange is disrupting the traditional wholesale deposit negotiation process with a digital solution that enables
                        Wholesale Depositors to connect directly with Canadian Financial Institutions. Our platform offers a secure, transparent,
                        efficient and low cost solution to negotiate GIC rates with multiple financial institutions - without the cost and time
                        involved with using a middleman.
                    </p>
                    <p>
                        We’re excited to offer a unique solution that allows the wholesale deposit market function as it should. Yield Exchange
                        gives depositors the confidence to place funds at the going rate, while completing due diligence. For Financial Institutions,
                        Yield Exchange enables you to be notified on all market activity so you can focus on requests that align with your liquidity needs.
                    </p>
                </div>
            </div>
        </div>

        <div class="card-spacer"></div>
        <div class="col-md-12 col-padding-remover">
            <div class="card card-shadow card-center">
                <div class="card-body">
                    <h3>Leadership Team</h3>
                </div>
            </div>
        </div>

        <div class="card-spacer"></div>
        <div class="col-md-12 col-padding-remover">
            <div class="card-center">
                <about-us-team-member v-bind:key="'about-us-team-member'+lead.id" v-for="lead in leads" :data="lead" />
            </div>
        </div>

        <div class="card-spacer"></div>
        <div class="col-md-12 col-padding-remover">
            <div class="card card-shadow card-center">
                <div class="card-body">
                    <h3>Advisors</h3>
                </div>
            </div>
        </div>

        <div class="card-spacer"></div>

        <div class="col-md-12 col-padding-remover">
            <div class="card-center">
                <hooper :settings="hooperSettings">
                    <slide  v-for="(adviser, indx) in advisers" :key="indx" :index="indx">
                        <about-us-team-member :data="adviser" />
                    </slide>
                </hooper>
            </div>
        </div>

        <div class="card-spacer" style="height:200px"></div>
    </div>
</template>
<script>
    import { Hooper,Slide } from 'hooper';
    import 'hooper/dist/hooper.css';

    export default {
        components: {
            Hooper,
            Slide
        },
        props: [],
        data() {
            return {
                hooperSettings: {
                    itemsToShow: 1.17,
                    centerMode: true,
                    autoPlay: true,
                    playSpeed: 15000,
                    transition: 300,
                    infiniteScroll: true
                },
                leads: [
                    {
                        "id":1,
                        "name": "Yvette Wu",
                        "role": "Chief Executive Officer",
                        "pic": "/teams/Icon.png",
                        "description": "Yvette has over 15 years of experience in banking and strategic consulting. She designed and implemented Vancity’s first board approved Enterprise Risk Management Framework. Yvette has led within Fortune 500 companies, medium and large-scale social enterprises, and fast-paced tech startups. Her diverse industry and corporate background lend a unique mash up of experiences and skill sets. She has supported many brands including Best Buy, Aritzia, Sony Image Works, Innovate BC, The Forum, League of Innovators, Procurify, Canadian Health Institute for Research, and more. She was recently nominated for the RBC Women in Entrepreneur Award - “Ones to Watch” and was also featured in Vancouver Tech Journal."
                    },
                    {
                        "id":2,
                        "name": "Sampath Ekanayake",
                        "role": "Chief Product Officer",
                        "pic": "/teams/sam.png",
                        "description": "Sampath is a certified Financial Risk Manager with over 18 years of banking experience. He led his team to design, develop and implement a Banking system which received the “best quality software” award under the “financial category” at the National Best Quality Software Awards (NBQSA) organized by British Computer Society in 2012. Sampath developed and implemented Vancity Credit Union’s commercial risk rating model in 2017. He specializes in capital, liquidity, retail and commercial lending model development and validation."
                    },
                    {
                        "id":3,
                        "name": "Ravi Sumal",
                        "role": "Chief Operating Officer",
                        "pic": "/teams/ravi.png",
                        "description": "Ravi is a certified PMP with over 10 years of experience in marketing, wealth management, technology and data and analytics in the banking industry. Ravi led responsible for delivering a modern information management and analytics system to enable self-service business intelligence. The team received an award for the best data management solution in digital transformation. He specializes in application and product development."
                    },
                ],
                advisers: [
                    // {
                    //     "id":4,
                    //     "name": "Heather Kaart",
                    //     "role": "IT Banking Leader",
                    //     "pic": "/teams/heather.png",
                    //     "description": "Heather Kaart has been leading teams in the financial services industry for 15 years. During this period, she implemented several new programs for HSBC North America. She led a large business transformation project replacing core banking and several related systems, and reworking business processes across the entire organization. Heather led the data analytics group for Vancity Credit Union. Although she is retired, she continues to provide strategic consulting services for Yield Exchange, and chairs her local library board."
                    // },
                    {
                        "id":5,
                        "name": "Glen Lougheed",
                        "role": "Tech entrepreneur",
                        "pic": "/teams/Glen.png",
                        "description": "Glen is a serial tech entrepreneur, angel investor and board member of InBC. Since November 2017, he has served as special advisor, innovation and technology to the minister of jobs, economic recovery and innovation. Lougheed is the CEO of Backrail Labs and Applied Post, and prior to this he was the CEO of Over interactive Media and NodeFly. He has also served as a mentor and advisor numerous times including with e@UBC and Youth Innovation Labs."
                    },
                    {
                        "id":6,
                        "name": "Jameel Sayani",
                        "role": "Regional MNP Partner",
                        "pic":"/teams/jameel.png",
                        "description": "Jameel is the Regional Managing Partner for MNP's Northern B.C. Region. Jameel also leads the Indigenous Services practice for MNP's Vancouver Island and Northern B.C. regions. Working closely with First Nations and Indigenous organizations. He was awarded Top 20 Under 40 for Vancouver Island and currently serves as a board member for Innovate BC."
                    },
                    {
                        "id":7,
                        "name": "Monique Morden",
                        "role": "Founder JUDI.AI",
                        "pic":"/teams/Monique.png",
                        "description": "Founder and past CEO JUDI.AI. Monique's 15+ years SaaS software background involves working in fintech and customer experience. She has experience in Services, Sales, Marketing and Product Development. Analytics and modelling is at the core whether a cash flow forecasting tool, risk management, customer experience or any other data driven technology."
                    },
                ]
            }
        },
        computed: {},
        methods: {}
    }
</script>
<style>
    .card-img-top{
        padding-top: 0!important;
    }
    .hooper,.hooper-list{
        height: auto!important;
    }
    .hooper-slide.is-prev{
        margin-left: -20px!important;
    }
    .hooper-slide.is-current {
        margin-right: 2%!important;
        margin-left: 2%!important;
    }
    .hooper-slide .card-img-top{
        /*height: 230px;*/
    }
</style>