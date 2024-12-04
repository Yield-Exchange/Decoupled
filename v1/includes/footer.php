
<!-- ====== Footer Part HTML Start ====== -->

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-4"></div>
            <div class="col-lg-3 col-sm-3">
                <div class="footer-item first-footer">
                    <a href="index" class="footer-logo">
                        <img src="<?php echo BASE_URL.'/assets/images/logo_light.png'?>" alt="Logo" style="width: 100%" />
                    </a>
                    <ul>
                        <li><a href="mailTo:info@yieldexchange.ca">info@yieldexchange.ca</a></li>
                        <!-- <li><a href="tel:+1 778 918 7735">+1 778 918 7735</a></li> -->
                    </ul>
                </div>
            </div>

<!--            <div class="col-lg-3 col-sm-3">-->
<!--                <div class="footer-item">-->
<!--                    <h3 style="margin-bottom: 2px">Quick Links</h3>-->
<!--                    <ul style="margin-top: 2px">-->
<!--                        <li class="--><?php //echo in_array($current_file,['terms_condition']) ? 'active' : '';?><!--"><a href="terms_condition">Terms &amp; Conditions</a></li>-->
<!--                        <li class="--><?php //echo in_array($current_file,['privacy_policy']) ? 'active' : '';?><!--"><a href="privacy_policy">Privacy and cookie policy</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</footer>
<section id="cpryt">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <p><?php echo date('Y');?> Yield Exchange Inc.</p>
            </div>
        </div>
    </div>
</section>

<!-- ====== Footer Part HTML End ====== -->

<!--====== Popper js ======-->
<script src="assets/js/Popper.js"></script>

<!--====== Bootstrap js ======-->
<script src="assets/js/bootstrap.min.js"></script>

<!--====== Jquery easing js ======-->
<script src="assets/js/jquery.easing.min.js"></script>

<!--====== Slick Slider js ======-->
<script src="assets/js/slick.min.js"></script>

<!--====== meanmenu js ======-->
<script src="assets/js/venobox.min.js"></script>

<!--====== Venobox popup js ======-->
<script src="assets/js/jquery.meanmenu.js"></script>

<!--====== Main js ======-->
<script src="assets/js/main.js?v=1.0"></script>
<script>
    window.addEventListener( "pageshow", function ( event ) {
        let historyTraversal = event.persisted ||
            ( typeof window.performance != "undefined" &&
                window.performance.navigation.type === 2 );
        if ( historyTraversal ) {
            // Handle page restore.
            window.location.reload();
        }
    });
</script>


</body>

</html>