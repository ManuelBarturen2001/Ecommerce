@php
    $footerInfo = Cache::rememberForever('footer_info', function(){
                return \App\Models\FooterInfo::first();
    });
@endphp
<div class="main-footer">


<footer class="footer">
<div class="footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="footer__copyright">
                        <p>{{@$footerInfo->copyright}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<style>
    .footer{padding:3px;text-align:center}
    .footer {
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        /* -premailer-width: 570px; */
        margin: 0 auto;
        padding: 0;
        text-align: center;
        width: auto;
        height: auto;
    }
    .footer__bottom {
        background-color: var(--general);
        padding: 15px 0;
        margin-top: 30px;
    }

    .footer__copyright {
        display: flex;
        justify-content: center;
        align-items: center;
    }

        .footer__copyright p {
        margin: 0;
        color: var(--white);
        font-size: 14px;
        font-weight: 400;
        text-align: center;
    }

</style>