<?php
session_start();
if (($_SESSION['login']) AND ($_SESSION['password'])) {
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
} else {
    $_SESSION['login'] = 0;
    $_SESSION['password'] = 0;
    header('Location: login.php');
    exit();
}

// Load the existing config
$c_config = file_get_contents('../append/price.json');
$c_config = json_decode($c_config, true);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle restoring defaults
    if (isset($_GET['restore']) && $_GET['restore'] == 'true') {
        // Restore default settings (existing code)
        // ...
    } else {
        // Update constants
        $c_config['constant']['show_auct_copart'] = isset($_POST['show_auct_copart']) ? 'on' : '';
        $c_config['constant']['show_auct_iaai'] = isset($_POST['show_auct_iaai']) ? 'on' : '';
        $c_config['constant']['show_auct_manheim'] = isset($_POST['show_auct_manheim']) ? 'on' : '';

        $c_config['constant']['color_1'] = $_POST['color_1'];
        $c_config['constant']['color_2'] = $_POST['color_2'];
        $c_config['constant']['color_3'] = $_POST['color_3'];
        $c_config['constant']['color_4'] = $_POST['color_4'];

        $c_config['constant']['show_country_usa'] = isset($_POST['show_country_usa']) ? 'on' : '';
        $c_config['constant']['show_country_canada'] = isset($_POST['show_country_canada']) ? 'on' : '';
        $c_config['constant']['show_country_ukraine'] = isset($_POST['show_country_ukraine']) ? 'on' : '';
        $c_config['constant']['show_country_germany'] = isset($_POST['show_country_germany']) ? 'on' : '';
        $c_config['constant']['show_country_lithuania'] = isset($_POST['show_country_lithuania']) ? 'on' : '';
        $c_config['constant']['show_country_georgia'] = isset($_POST['show_country_georgia']) ? 'on' : '';

        $c_config['constant']['company_name'] = $_POST['company_name'];

        $c_config['constant']["akciz_do_3"] = $_POST['akciz_do_3'];
        $c_config['constant']["akciz_ot_3"] = $_POST['akciz_ot_3'];
        $c_config['constant']["akciz_diesel_do_3_5"] = $_POST['akciz_diesel_do_3_5'];
        $c_config['constant']["akciz_diesel_ot_3_5"] = $_POST['akciz_diesel_ot_3_5'];
        $c_config['constant']["poshlina"] = $_POST['poshlina'];
        $c_config['constant']["nds"] = $_POST['nds'];
        $c_config['constant']["oblagayemaya_dostavka"] = $_POST['oblagayemaya_dostavka'];
        $c_config['constant']["otpravka_post"] = $_POST['otpravka_post'];
        $c_config['constant']["vygruzka"] = $_POST['vygruzka'];
        $c_config['constant']["class_1"] = $_POST['class_1'];
        $c_config['constant']["class_2"] = $_POST['class_2'];
        $c_config['constant']["class_3"] = $_POST['class_3'];
        $c_config['constant']["diller_1"] = $_POST['diller_1'];
        $c_config['constant']["diller_2"] = $_POST['diller_2'];
        $c_config['constant']["diller_3"] = $_POST['diller_3'];

        $c_config['constant']["show_send2email"] = isset($_POST['show_send2email']) ? 'on' : '';

        // Handle removing a state
        if (isset($_POST['remove_state'])) {
            $remove_index = $_POST['remove_state'];
            if (isset($c_config['states'][$remove_index])) {
                array_splice($c_config['states'], $remove_index, 1);
            }
        }

        // Update existing states
        $states = array();
        $state_count = count($c_config['states']);
        for ($n = 0; $n < $state_count; $n++) {
            // Skip the removed state
            if (isset($_POST['states_n_' . $n . '_Code_state'])) {
                $states[] = array(
                    'Code_state' => $_POST['states_n_' . $n . '_Code_state'],
                    'Name_state' => $_POST['states_n_' . $n . '_Name_state'],
                    'Price_port_USA' => $_POST['states_n_' . $n . '_Price_port_USA'],
                    'Port_USA' => $_POST['states_n_' . $n . '_Port_USA']
                );
            }
        }
        $c_config['states'] = $states;

        // Handle adding a new state
        if (isset($_POST['add_state'])) {
            $new_state = array(
                'Code_state' => $_POST['new_Code_state'],
                'Name_state' => $_POST['new_Name_state'],
                'Price_port_USA' => $_POST['new_Price_port_USA'],
                'Port_USA' => $_POST['new_Port_USA']
            );
            $c_config['states'][] = $new_state;
        }

        // Process other arrays (usa_to_other_country, copart, iaai) similarly...
        // Existing code for updating these arrays

        // Save updated config to the JSON file
        $new_config = json_encode($c_config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents('../append/price.json', $new_config);

        // Update style files (existing code)
        // ...

        // Redirect to avoid resubmission
        header('Location: index.php');
        exit();
    }
} else if (isset($_GET['restore']) && $_GET['restore'] == 'true') {
	//восстанавливаем все по дефолту
	$c_config = file_get_contents('../append/price.cfg');
	file_put_contents('../append/price.json', $c_config);
	
	$c_config = file_get_contents('../append/style.cfg');
	file_put_contents('../append/style.css', $c_config);
	
	$c_config = file_get_contents('../append/ukraine_blue.cfg');
	file_put_contents('../append/ukraine_blue.svg', $c_config);
	
	$c_config = file_get_contents('../append/germany_blue.cfg');
	file_put_contents('../append/germany_blue.svg', $c_config);
	
	$c_config = file_get_contents('../append/georgia_blue.cfg');
	file_put_contents('../append/georgia_blue.svg', $c_config);
	
	$c_config = file_get_contents('../append/canada_blue.cfg');
	file_put_contents('../append/canada_blue.svg', $c_config);
	
	header('Location: index.php');
	exit;
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Setting | Delivery Calculator</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">    
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- summernote CSS
		============================================ -->
    <link rel="stylesheet" href="css/summernote/summernote.css">
    <!-- Range Slider CSS
		============================================ -->
    <link rel="stylesheet" href="css/themesaller-forms.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- bootstrap select CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap-select/bootstrap-select.css">
    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="css/datapicker/datepicker3.css">
    <!-- Color Picker CSS
		============================================ -->
    <link rel="stylesheet" href="css/color-picker/farbtastic.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/chosen/chosen.css">
    <!-- notification CSS
		============================================ -->
    <link rel="stylesheet" href="css/notification/notification.css">
    <!-- dropzone CSS
		============================================ -->
    <link rel="stylesheet" href="css/dropzone/dropzone.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Start Header Top Area -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="logo-area">
                        <h1 style="color: #fff;">Delivery Calculator</h1>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="header-top-menu">
                        <ul class="nav navbar-nav notika-top-nav">
                            <li class="nav-item dropdown">
                              <a href="mailto:development.cx.ua@gmail.com">  <i class="notika-icon notika-chat"></i> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
 
  <form action="" method="post">
  
    <!-- Start Status area -->
    <div class="notika-status-area">
        <div class="container">
            <div class="row">
				<br>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										<i class="notika-icon notika-form"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>Настройки калькулятора растаможки авто.</h2>
										<p>После выбора параметров не забывайте их <span class="bread-ntd">Сохранить</span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- отображение клавиш аукционов -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="basic-tb-hd">
                            <h2>Отображение клавиш аукционов</h2>
                            <p>Можно скрыть аукцион из расчета, кликнув по переключателю</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="green">
                                                <label for="ts1" class="ts-label">Copart</label>
                                                <input id="ts1" type="checkbox" hidden="hidden" name="show_auct_copart" <?php if ($c_config['constant']['show_auct_copart']=='on'){ echo 'checked="true"'; }?> >
                                                <label for="ts1" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="blue">
                                                <label for="ts2" class="ts-label">IAAI</label>
                                                <input id="ts2" type="checkbox" hidden="hidden" name="show_auct_iaai" <?php if ($c_config['constant']['show_auct_iaai']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="ts2" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="pink">
                                                <label for="ts3" class="ts-label">Manheim</label>
                                                <input id="ts3" type="checkbox" hidden="hidden" name="show_auct_manheim" <?php if ($c_config['constant']['show_auct_manheim']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="ts3" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				
				<!--- цветовая гамма ----->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="colorpicker-int mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Цветовая гамма</h2>
                            <p>Для того что бы оставить значение по умолчанию, оставьте поле пустым</p>
                        </div>
						
                        <div class="row" id="form_colors">					
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-container fm-cmp-mg">
                                    <div class="input-group form-group form-elet-mg ic-cmp-int float-lb form-elet-mg">
									
                                        <span class="input-group-addon"><i class="notika-icon notika-success"></i></span>
                                       
									   <div class="nk-line dropdown nk-int-st nk-toggled">
											<label class="nk-label">Основной цвет клавиш</label>
                                            <input type="text" class="form-control nk-value" name="color_1"  value="<?php echo $c_config['constant']['color_1'];?>" data-toggle="dropdown" style="background-color: <?php echo $c_config['constant']['color_1'];?>; color: rgb(255, 255, 255);" aria-expanded="false!">
                                            <div class="dropdown-menu">
                                                <div class="color-picker" data-cp-default="<?php echo $c_config['constant']['color_1'];?>"><div class="farbtastic"><div class="color" style="background-color: rgb(0, 255, 192);"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 119px; top: 178px;"></div><div class="sl-marker marker" style="left: 47px; top: 109px;"></div></div></div>
                                            </div>
                                            <i class="nk-value" style="background-color: <?php echo $c_config['constant']['color_1'];?>; color: rgb(255, 255, 255);"></i>
                                        </div>
										
                                    </div>
                                </div>
                            </div>
							
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-container fm-cmp-mg">
                                    <div class="input-group form-group form-elet-mg ic-cmp-int float-lb form-elet-mg">
                                        <span class="input-group-addon"><i class="notika-icon notika-success"></i></span>
                                        <div class="nk-line dropdown nk-int-st nk-toggled">
										<label class="nk-label">Цвет текста на клавишах</label>
                                            <input type="text" class="form-control nk-value"  name="color_2"  value="<?php echo $c_config['constant']['color_2'];?>" data-toggle="dropdown" style="background-color: <?php echo $c_config['constant']['color_2'];?>; color: rgb(0, 0, 0);">
                                            <div class="dropdown-menu">
                                                <div class="color-picker" data-cp-default="<?php echo $c_config['constant']['color_2'];?>"><div class="farbtastic"><div class="color" style="background-color: rgb(137, 255, 0);"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 181px; top: 94px;"></div><div class="sl-marker marker" style="left: 97px; top: 94px;"></div></div></div>
                                            </div>
                                            <i class="nk-value" style="background-color: <?php echo $c_config['constant']['color_2'];?>; color: rgb(0, 0, 0);"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-container fm-cmp-mg">
                                    <div class="input-group form-group form-elet-mg ic-cmp-int float-lb form-elet-mg">
                                        <span class="input-group-addon"><i class="notika-icon notika-success"></i></span>
                                        <div class="nk-line dropdown nk-int-st nk-toggled">
										<label class="nk-label">Цвет плашек</label>
										
                                            <input type="text" class="form-control nk-value"  name="color_3"   value="<?php echo $c_config['constant']['color_3'];?>" data-toggle="dropdown" style="background-color: <?php echo $c_config['constant']['color_3'];?>; color: rgb(0, 0, 0);">
											
                                            <div class="dropdown-menu">
                                                <div class="color-picker" data-cp-default="<?php echo $c_config['constant']['color_3'];?>"><div class="farbtastic"><div class="color" style="background-color: <?php echo $c_config['constant']['color_3'];?>;"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 103px; top: 13px;"></div><div class="sl-marker marker" style="left: 57px; top: 89px;"></div></div></div>
                                            </div>
                                            <i class="nk-value" style="background-color: <?php echo $c_config['constant']['color_3'];?>; color: rgb(0, 0, 0);"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-container fm-cmp-mg">
                                    <div class="input-group form-group form-elet-mg ic-cmp-int float-lb form-elet-mg">
                                        <span class="input-group-addon"><i class="notika-icon notika-success"></i></span>
                                        <div class="nk-line dropdown nk-int-st nk-toggled">
										<label class="nk-label">Цвет текста на плашках</label>	
										
                                            <input type="text" class="form-control nk-value"  name="color_4" value="<?php echo $c_config['constant']['color_4'];?>" data-toggle="dropdown" style="background-color: <?php echo $c_config['constant']['color_4'];?>; color: rgb(0, 0, 0);">
											
                                            <div class="dropdown-menu">
                                                <div class="color-picker" data-cp-default="<?php echo $c_config['constant']['color_4'];?>"><div class="farbtastic"><div class="color" style="background-color: <?php echo $c_config['constant']['color_4'];?>;"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 103px; top: 13px;"></div><div class="sl-marker marker" style="left: 57px; top: 89px;"></div></div></div>
                                            </div>
                                            <i class="nk-value" style="<?php echo $c_config['constant']['color_4'];?>; color: rgb(0, 0, 0);"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<!--- ----->
				
				<!-- отображение стран доставки -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="basic-tb-hd">
                            <h2>Отображение стран доставки</h2>
                            <p>Можно скрыть страну из расчета, кликнув по переключателю</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="green">
                                                <label for="country1" class="ts-label">США</label>
                                                <input id="country1" type="checkbox" hidden="hidden"  name="show_country_usa"   <?php if ($c_config['constant']['show_country_usa']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country1" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="blue">
                                                <label for="country2" class="ts-label">Канада</label>
                                                <input id="country2" type="checkbox" hidden="hidden" name="show_country_canada" <?php if ($c_config['constant']['show_country_canada']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country2" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="pink">
                                                <label for="country3" class="ts-label">Украина</label>
                                                <input id="country3" type="checkbox" hidden="hidden" name="show_country_ukraine" <?php if ($c_config['constant']['show_country_ukraine']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country3" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="pink">
                                                <label for="country4" class="ts-label">Германия</label>
                                                <input id="country4" type="checkbox" hidden="hidden" name="show_country_germany" <?php if ($c_config['constant']['show_country_germany']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country4" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="pink">
                                                <label for="country5" class="ts-label">Литва</label>
                                                <input id="country5" type="checkbox" hidden="hidden" name="show_country_lithuania" <?php if ($c_config['constant']['show_country_lithuania']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country5" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="pink">
                                                <label for="country6" class="ts-label">Грузия</label>
                                                <input id="country6" type="checkbox" hidden="hidden" name="show_country_georgia" <?php if ($c_config['constant']['show_country_georgia']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="country6" class="ts-helper"></label>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				<!--- Кофициэнты -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Динамические параметры</h2>
                            <p></p>
                        </div>
                        <div class="row" id="form_config">
						
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-support"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control" name="company_name"  value="<?php echo $c_config['constant']['company_name'];?>">
                                        <label class="nk-label"><b>Название компании</b></label>
                                    </div>
                                </div>
                            </div>
                           
						   <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="akciz_do_3"  value="<?php echo $c_config['constant']['akciz_do_3'];?>">
                                        <label class="nk-label">Акциз на бенз. ДВС до 3л.</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="akciz_ot_3"  value="<?php echo $c_config['constant']['akciz_ot_3'];?>">
                                        <label class="nk-label">Акциз на бенз. ДВС от 3л.</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="akciz_diesel_do_3_5"  value="<?php echo $c_config['constant']['akciz_diesel_do_3_5'];?>">
                                        <label class="nk-label">Акциз на дизельн. ДВС до 3л.</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="akciz_diesel_ot_3_5"  value="<?php echo $c_config['constant']['akciz_diesel_ot_3_5'];?>">
                                        <label class="nk-label">Акциз на дизельн. ДВС от 3л.</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="poshlina"  value="<?php echo $c_config['constant']['poshlina'];?>">
                                        <label class="nk-label">Пошлина</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="nds"  value="<?php echo $c_config['constant']['nds'];?>">
                                        <label class="nk-label">НДС</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="oblagayemaya_dostavka"  value="<?php echo $c_config['constant']['oblagayemaya_dostavka'];?>">
                                        <label class="nk-label">Облагаемая доставка</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="otpravka_post"  value="<?php echo $c_config['constant']['otpravka_post'];?>">
                                        <label class="nk-label">Отправка международной почты</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="vygruzka"  value="<?php echo $c_config['constant']['vygruzka'];?>">
                                        <label class="nk-label">Экспедирование + Брокер</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="class_1"  value="<?php echo $c_config['constant']['class_1'];?>">
                                        <label class="nk-label">Класс 1</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="class_2"  value="<?php echo $c_config['constant']['class_2'];?>">
                                        <label class="nk-label">Класс 2</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="class_3"  value="<?php echo $c_config['constant']['class_3'];?>">
                                        <label class="nk-label">Класс 3</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="diller_1"  value="<?php echo $c_config['constant']['diller_1'];?>">
                                        <label class="nk-label">Диллер 1</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="diller_2"  value="<?php echo $c_config['constant']['diller_2'];?>">
                                        <label class="nk-label">Диллер 2</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-dollar"></i>
                                    </div>
                                    <div class="nk-int-st nk-toggled">
                                        <input type="text" class="form-control"  name="diller_3"  value="<?php echo $c_config['constant']['diller_3'];?>">
                                        <label class="nk-label">Диллер 3</label>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="toggle-select-act mg-t-30">
                                            <div class="nk-toggle-switch" data-ts-color="blue">
                                                <label for="send2email" class="ts-label">Показывать блок отправки данных на почту?</label>
                                                <input id="send2email" type="checkbox" hidden="hidden" name="show_send2email" <?php if ($c_config['constant']['show_send2email']=='on'){ echo 'checked="true"'; }?>>
                                                <label for="send2email" class="ts-helper"></label>
                                            </div>
                                        </div> 
                            </div>
							
                        </div>
                    </div>
                </div>
				<!--- --->	


<!--- Доставка по США -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Параметры доставки в порт США </h2>
                            <p>Код штата; Штат; Цена доставки в порт; Код порта</p>
                        </div>
                        <div class="row" id="form_config_tr">
                            <div class="bsc-tbl-hvr">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code_state</th>
                                            <th>Name_state</th>
                                            <th>Price_port_USA</th>
                                            <th>Port_USA</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Display existing states
                                        for ($s = 0; $s < count($c_config['states']); $s++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $s + 1; ?></td>
                                            <td><input name="states_n_<?php echo $s; ?>_Code_state" value="<?php echo htmlspecialchars($c_config['states'][$s]['Code_state']); ?>"></td>
                                            <td><input name="states_n_<?php echo $s; ?>_Name_state" value="<?php echo htmlspecialchars($c_config['states'][$s]['Name_state']); ?>"></td>
                                            <td><input name="states_n_<?php echo $s; ?>_Price_port_USA" value="<?php echo htmlspecialchars($c_config['states'][$s]['Price_port_USA']); ?>"></td>
                                            <td><input name="states_n_<?php echo $s; ?>_Port_USA" value="<?php echo htmlspecialchars($c_config['states'][$s]['Port_USA']); ?>"></td>
                                            <td>
                                                <button class='btn btn-danger' type="submit" name="remove_state" value="<?php echo $s; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот штат?');">Удалить</button>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <!-- Row to add a new state -->
                                        <tr>
                                            <td>New</td>
                                            <td><input name="new_Code_state" placeholder="Код штата"></td>
                                            <td><input name="new_Name_state" placeholder="Штат"></td>
                                            <td><input name="new_Price_port_USA" placeholder="Цена доставки в порт"></td>
                                            <td><input name="new_Port_USA" placeholder="Код порта"></td>
                                            <td>
                                                <button class='btn btn-primary' type="submit" name="add_state" value="1">Добавить</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
<!--- Из США в другие страны -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Из США в другие страны</h2>
                            <p>Порт; Код порта; Цена доставки в Украину; Цена доставки в Германию; Цена доставки в Грузию</p>
                        </div>
                        <div class="row">
						
						<div class="bsc-tbl-hvr">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>name</th>
                                        <th>code</th>
                                        <th>price_ukraine</th>
                                        <th>price_germany</th>
										<th>price_georgia</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									//states
									/*usa_to_other_country
										"name":"New York",
										"code":"NY",
										"price_ukraine":"850",
										"price_germany":"850",
										"price_georgia":"850" */
									
										for($s=0;$s<count($c_config['usa_to_other_country']);$s++){
											
									
									?>
	<tr>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>" value="<?php echo $s;?>" disabled></td>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>_name" value="<?php echo $c_config['usa_to_other_country'][$s]['name'];?>"></td>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>_code" value="<?php echo $c_config['usa_to_other_country'][$s]['code'];?>"></td>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>_price_ukraine" value="<?php echo $c_config['usa_to_other_country'][$s]['price_ukraine'];?>"></td>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>_price_germany" value="<?php echo $c_config['usa_to_other_country'][$s]['price_germany'];?>"></td>
		<td><input name="usa_to_other_country_n_<?php echo $s;?>_price_georgia" value="<?php echo $c_config['usa_to_other_country'][$s]['price_georgia'];?>"></td>
	</tr>
									<?php
									//exit;
										}
									?>						
								</tbody>
								</table>
							</div>
						
						</div>
					</div>
				</div>
	

<!--- Доставка Copart -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Доставка Copart</h2>
                            <p></p>
                        </div>
                        <div class="row">
						
						<div class="bsc-tbl-hvr">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>range_price</th>
                                        <th>fee</th>
                                        <th>Gate</th>
                                        <th>Internet_fee</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									/* "copart":[
											"range_price":"0.01 - 49.99",
											"fee":"1",
											"Gate":"59",
											"Internet_fee":"0" */

										for($s=0;$s<count($c_config['copart']);$s++){
											
									?>
	<tr>
		<td><input name="copart_n_<?php echo $s;?>" value="<?php echo $s;?>" disabled></td>
		<td><input name="copart_n_<?php echo $s;?>_range_price" value="<?php echo $c_config['copart'][$s]['range_price'];?>"></td>
		<td><input name="copart_n_<?php echo $s;?>_fee" value="<?php echo $c_config['copart'][$s]['fee'];?>"></td>
		<td><input name="copart_n_<?php echo $s;?>_Gate" value="<?php echo $c_config['copart'][$s]['Gate'];?>"></td>
		<td><input name="copart_n_<?php echo $s;?>_Internet_fee" value="<?php echo $c_config['copart'][$s]['Internet_fee'];?>"></td>
	</tr>
									<?php
									//exit;
										}
									?>						
								</tbody>
								</table>
							</div>
						
						</div>
					</div>
				</div>
	
	
<!--- Доставка iaai -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Доставка iaai</h2>
                            <p></p>
                        </div>
                        <div class="row">
						
						<div class="bsc-tbl-hvr">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>range_price</th>
                                        <th>fee</th>
                                        <th>Gate</th>
                                        <th>Internet_fee</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									/* "iaai":[
											"range_price":"0.01 - 49.99",
											"fee":"1",
											"Gate":"59",
											"Internet_fee":"0" */

										for($s=0;$s<count($c_config['iaai']);$s++){
											
									?>
	<tr>
		<td><input name="iaai_n_<?php echo $s;?>" value="<?php echo $s;?>" disabled></td>
		<td><input name="iaai_n_<?php echo $s;?>_range_price" value="<?php echo $c_config['iaai'][$s]['range_price'];?>"></td>
		<td><input name="iaai_n_<?php echo $s;?>_fee" value="<?php echo $c_config['iaai'][$s]['fee'];?>"></td>
		<td><input name="iaai_n_<?php echo $s;?>_Gate" value="<?php echo $c_config['iaai'][$s]['Gate'];?>"></td>
		<td><input name="iaai_n_<?php echo $s;?>_Internet_fee" value="<?php echo $c_config['iaai'][$s]['Internet_fee'];?>"></td>
	</tr>
									<?php
									//exit;
										}
									?>						
								</tbody>
								</table>
							</div>
						
						</div>
					</div>
				</div>
	
				
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btn-demo-notika" style="margin-top:10px;">
							<div class="btn-list">
								<button class="btn btn-success notika-btn-success waves-effect">Сохранить настройки</button>
								</form>
								<a href="?restore=true" class="btn btn-danger notika-btn-danger waves-effect">Сбросить настройки</a>
							</div>
						</div>
					</div>
				
            </div>
        </div>
    </div>
    <!-- End Status area-->
    <!-- Start Sale Statistic area-->



    <!-- End Realtime sts area-->
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2020. All rights reserved. web: <a href="https://development.cx.ua">development.cx.ua</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer area-->
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="js/knob/jquery.knob.js"></script>
    <script src="js/knob/jquery.appear.js"></script>
    <script src="js/knob/knob-active.js"></script>
    <!-- Input Mask JS
		============================================ -->
    <script src="js/jasny-bootstrap.min.js"></script>
    <!-- icheck JS
		============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!-- rangle-slider JS
		============================================ -->
    <script src="js/rangle-slider/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="js/rangle-slider/jquery-ui-touch-punch.min.js"></script>
    <script src="js/rangle-slider/rangle-active.js"></script>
    <!-- datapicker JS
		============================================ -->
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <!-- bootstrap select JS
		============================================ -->
    <script src="js/bootstrap-select/bootstrap-select.js"></script>
    <!--  color-picker JS
		============================================ -->
    <script src="js/color-picker/farbtastic.min.js"></script>
    <script src="js/color-picker/color-picker.js"></script>
    <!--  notification JS
		============================================ -->
    <script src="js/notification/bootstrap-growl.min.js"></script>
    <script src="js/notification/notification-active.js"></script>
    <!--  summernote JS
		============================================ -->
    <script src="js/summernote/summernote-updated.min.js"></script>
    <script src="js/summernote/summernote-active.js"></script>
    <!-- dropzone JS
		============================================ -->
    <script src="js/dropzone/dropzone.js"></script>
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!--  chosen JS
		============================================ -->
    <script src="js/chosen/chosen.jquery.js"></script>
    <!--  Chat JS
		============================================ -->
    <script src="js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="js/todo/jquery.todo.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>

		<script>
			$('#form_colors input').on('focusout blur', function() {
				$('#form_colors .input-group.form-group .nk-line.dropdown').attr('class', 'nk-line dropdown nk-int-st nk-toggled');
			}); 
			
			$('#form_config input').on('focusout blur', function() {
				$('#form_config .nk-int-st').attr('class', 'nk-int-st nk-toggled');
			}); 

						
		</script>
</body>

</html>
