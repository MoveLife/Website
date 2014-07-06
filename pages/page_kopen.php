<?php
	require dirname(__FILE__).'/../inc/access.php';
        $PAGE['css'] = 'body{padding-top:20px}

            .pricing-table .plan {
                margin-left:0px;
                border-radius: 5px;
                text-align: center;
                background-color: #f3f3f3;
                -moz-box-shadow: 0 0 6px 2px #b0b2ab;
                -webkit-box-shadow: 0 0 6px 2px #b0b2ab;
                box-shadow: 0 0 6px 2px #b0b2ab;
            }

            .plan:hover {
                background-color: #fff;
                -moz-box-shadow: 0 0 12px 3px #b0b2ab;
                -webkit-box-shadow: 0 0 12px 3px #b0b2ab;
                box-shadow: 0 0 12px 3px #b0b2ab;
            }

            .plan {
                padding: 20px;
                margin-left:0px;
                color: #ff;
                background-color: #5e5f59;
                -moz-border-radius: 5px 5px 0 0;
                -webkit-border-radius: 5px 5px 0 0;
                border-radius: 5px 5px 0 0;
            }

            .plan-name-bronze {
                padding: 20px;
                color: #fff;
                background-color: #665D1E;
                -moz-border-radius: 5px 5px 0 0;
                -webkit-border-radius: 5px 5px 0 0;
                border-radius: 5px 5px 0 0;
            }

            .plan-name-silver {
                padding: 20px;
                color: #fff;
                background-color: #C0C0C0;
                -moz-border-radius: 5px 5px 0 0;
                -webkit-border-radius: 5px 5px 0 0;
                border-radius: 5px 5px 0 0;
            }

            .plan-name-gold {
                padding: 20px;
                color: #fff;
                background-color: #FFD700;
                -moz-border-radius: 5px 5px 0 0;
                -webkit-border-radius: 5px 5px 0 0;
                border-radius: 5px 5px 0 0;
            } 

            .pricing-table-bronze  {
                padding: 20px;
                color: #fff;
                background-color: #f89406;
                -moz-border-radius: 5px 5px 0 0;
                -webkit-border-radius: 5px 5px 0 0;
                border-radius: 5px 5px 0 0;
            }

            .pricing-table .plan .plan-name span {
                font-size: 20px;
            }

            .pricing-table .plan ul {
                list-style: none;
                margin: 0;
                -moz-border-radius: 0 0 5px 5px;
                -webkit-border-radius: 0 0 5px 5px;
                border-radius: 0 0 5px 5px;
            }

            .pricing-table .plan ul li.plan-feature {
                padding: 15px 10px;
                border-top: 1px solid #c5c8c0;
                margin-right: 35px;
            }

            .pricing-three-column {
                margin: 0 auto;
                width: 80%;
            }

            .pricing-variable-height .plan {
                float: none;
                margin-left: 2%;
                vertical-align: bottom;
                display: inline-block;
                zoom:1;
                *display:inline;
            }

            .plan-mouseover .plan-name {
                background-color: #4e9a06 !important;
            }

            .btn-plan-select {
                padding: 8px 25px;
                font-size: 18px;
            }';
?>
<div class="pricing-table pricing-three-column row">
                    <div class="plan col-sm-4 col-lg-4">
                        <div class="plan-name-bronze">
                            <h2>Bronze</h2>
                            <span>&euro;7.99 / Maand</span>
                        </div>
                        <ul>
                            <li class="plan-feature">5% Rating</li>
                            <li class="plan-feature">1 Gratis bedrijf</li>
                            <li class="plan-feature">&euro;10,- per extra bedrijf</li>
                            <li class="plan-feature"><a href="#" class="btn btn-primary btn-plan-select"><i class="icon-white icon-ok"></i> Selecteer</a></li>
                        </ul>
                    </div>
                    <div style="z-index:55;" class="plan col-sm-4 col-lg-4">
                        <div class="plan-name-silver">
                            <h2>Silver <span class="badge badge-warning">Populair</span></h2>
                            <span><strike>&euro;10.99/ Maand</strike> <span style="color:red"> $9.99 - <span class="label label-warning">Verkoop!</span></span></span>
                        </div>
                        <ul>
                            <li class="plan-feature">15% Rating</li>
                            <li class="plan-feature">2 Gratis bedrijven</li>
                            <li class="plan-feature">&euro;8.99 per extra bedrijf</li>
                            <li class="plan-feature"><a href="#" class="btn btn-primary btn-plan-select"><i class="icon-white icon-ok"></i> Selecteer</a></li>
                        </ul>
                    </div>
                    <div class="plan col-sm-4 col-lg-4">
                        <div class="plan-name-gold">
                            <h2>Gold</h2>
                            <span>&euro;14.99 / Maand</span>
                        </div>
                        <ul>
                            <li class="plan-feature">30% Rating</li>
                            <li class="plan-feature">5 Gratis bedrijven</li>
                            <li class="plan-feature">&euro;4.99 per extra bedrijf</li>
                            <li class="plan-feature"><a href="#" class="btn btn-primary btn-plan-select"><i class="icon-white icon-ok"></i> Selecteer</a></li>
                        </ul>
                    </div>
                </div>
            