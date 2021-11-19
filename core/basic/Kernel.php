<?php
namespace core\basic;
class Kernel {
	private static $xvapff37a0fb7eb34ffc195e385f2d1008d3Array;
	public static function run() {
		self:: cgvfcvlpn8229f69ecbdd7a63bfdf94eb205b79a1();
		$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = self:: ynanvapmsugf4ddd2ce505128ba666bd3b63264c6c5();
		$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = self:: qgcecdsfb9379c7f9197d7251b1ac03b4c839ee0($xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
		$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = self:: qgclgqanfc97cca716704034cd205b9457629322($xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
		$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5 = self:: ynaallnhhnvap9e3f7ca7c1fb0dbd479baa444e9cd011($xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
		$cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed = self:: gnyaxxnvap74670b424e8b3bda28fd8aa70829fa18($vllnhh_xvap6ed11be5257dc81dff031ea475448fd5);
		self:: cgvfcgff7fd1328d9c68d8303390e0b31404dc3a();
		self:: cgvfcgsaggccng446a06f26641e8be795aafe1c47d924f($cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed);
	}
	private static function ynanvapmsugf4ddd2ce505128ba666bd3b63264c6c5() {
		if (isset($_SERVER['PATH_INFO']) && ! mb_check_encoding($_SERVER['PATH_INFO'], 'UTF-8')) {
			$_SERVER['PATH_INFO'] = mb_convert_encoding($_SERVER['PATH_INFO'], 'utf-8', 'GBK');
		}
		if (isset($_SERVER['REQUEST_URI']) && ! mb_check_encoding($_SERVER['REQUEST_URI'], 'UTF-8')) {
			$_SERVER['REQUEST_URI'] = mb_convert_encoding($_SERVER['REQUEST_URI'], 'utf-8', 'GBK');
		}
		if (isset($_SERVER['ORIG_PATH_INFO']) && ! mb_check_encoding($_SERVER['ORIG_PATH_INFO'], 'UTF-8')) {
			$_SERVER['ORIG_PATH_INFO'] = mb_convert_encoding($_SERVER['ORIG_PATH_INFO'], 'utf-8', 'GBK');
		}
		$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = '';
		if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']) {
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = $_SERVER['PATH_INFO'];
		} elseif (isset($_SERVER["REDIRECT_URL"]) && $_SERVER["REDIRECT_URL"]) {
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = str_replace('/' . basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REDIRECT_URL']);
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = str_replace(SITE_DIR, '', $xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
			$_SERVER['PATH_INFO'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_info;
		}
		if (! $xvapff37a0fb7eb34ffc195e385f2d1008d3_info) {
			if (isset($_GET['p']) && $_GET['p']) {
				$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = $_GET['p'];
			} elseif (isset($_GET['s']) && $_GET['s']) {
				$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = $_GET['s'];
			}
		}
		if ($xvapff37a0fb7eb34ffc195e385f2d1008d3_info) {
			$xvaangs551982026e32b3092a35bcb13e5b367c = '{^\/?([\x{4e00}-\x{9fa5}\w\-\/\.' . Config::get('url_allow_char') . ']+?)?\/?$}u';
			if (preg_match($xvaangs551982026e32b3092a35bcb13e5b367c, $xvapff37a0fb7eb34ffc195e385f2d1008d3_info)) {
				$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = preg_replace($xvaangs551982026e32b3092a35bcb13e5b367c, '$1', $xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
				$qgc_pafc_hquude2070d237169ea353989ac074fe404ce0 = Config::get('url_rule_suffix');
				if (! ! $xghb748cb0af8ca725e7037151bbb5212e8 = strripos($xvapff37a0fb7eb34ffc195e385f2d1008d3_info, $qgc_pafc_hquude2070d237169ea353989ac074fe404ce0)) {
					$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = substr($xvapff37a0fb7eb34ffc195e385f2d1008d3_info, 0, $xghb748cb0af8ca725e7037151bbb5212e8);
				}
			} else {
				$fdndf19458d7b0b64930389a8f159617d2b = true;
			}
		}
		if (isset($fdndf19458d7b0b64930389a8f159617d2b) && $fdndf19458d7b0b64930389a8f159617d2b) {
			header('HTTP/1.1 404 Not Found');
			header('status: 404 Not Found');
			$fnunsf832f9a48287f57f772e025561ccaf0ef = ROOT_PATH . '/defend.html';
			if (file_exists($fnunsf832f9a48287f57f772e025561ccaf0ef)) {
				require $fnunsf832f9a48287f57f772e025561ccaf0ef;
				exit();
			} else {
				error('您访问路径含有非法字符，防注入系统提醒您请勿尝试非法操作！');
			}
		}
		define('P', $xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
		return $xvapff37a0fb7eb34ffc195e385f2d1008d3_info;
	}
	private static function qgcecdsfb9379c7f9197d7251b1ac03b4c839ee0($xvapff37a0fb7eb34ffc195e385f2d1008d3Info) {
		$xvapff37a0fb7eb34ffc195e385f2d1008d3 = '';
		if (! ! $fgfvdshddc2a7cab73cf88cb91ddb956428607c = Config::get('app_domain_bind')) {
			$hngzng_svfnde191c9495aad7920ea8c611f785971f = get_http_host();
			if (isset($fgfvdshddc2a7cab73cf88cb91ddb956428607c[$hngzng_svfnde191c9495aad7920ea8c611f785971f])) {
				$xvapff37a0fb7eb34ffc195e385f2d1008d3 = $fgfvdshddc2a7cab73cf88cb91ddb956428607c[$hngzng_svfnde191c9495aad7920ea8c611f785971f];
			}
		}
		if (defined('URL_BIND')) {
			if ($xvapff37a0fb7eb34ffc195e385f2d1008d3 && URL_BIND != $xvapff37a0fb7eb34ffc195e385f2d1008d3) {
				error('系统配置的模块域名绑定与入口文件绑定冲突，请核对！');
			} else {
				$xvapff37a0fb7eb34ffc195e385f2d1008d3 = URL_BIND;
			}
		}
		return $xvapff37a0fb7eb34ffc195e385f2d1008d3 ? trim_slash($xvapff37a0fb7eb34ffc195e385f2d1008d3) . '/' . $xvapff37a0fb7eb34ffc195e385f2d1008d3Info : $xvapff37a0fb7eb34ffc195e385f2d1008d3Info;
	}
	private static function qgclgqanfc97cca716704034cd205b9457629322($xvapff37a0fb7eb34ffc195e385f2d1008d3Info) {
		if (! ! $ggqan9490258937c7b166e5bfc30a4d64ff61 = Config::get('url_route')) {
			if (! $xvapff37a0fb7eb34ffc195e385f2d1008d3Info && isset($ggqan9490258937c7b166e5bfc30a4d64ff61['/'])) {
				return $ggqan9490258937c7b166e5bfc30a4d64ff61['/'];
			}
			foreach ($ggqan9490258937c7b166e5bfc30a4d64ff61 as $pnr134f0e49b939006a8aac23e71464cca0 => $zvcqne896e47f047ce9c7c3601cc8bf702f16) {
				$pnr134f0e49b939006a8aac23e71464cca0 = trim_slash($pnr134f0e49b939006a8aac23e71464cca0);
				$gnyf270a8e8d025528086f0dc997facb623 = "{" . $pnr134f0e49b939006a8aac23e71464cca0 . "}i";
				if (preg_match($gnyf270a8e8d025528086f0dc997facb623, $xvapff37a0fb7eb34ffc195e385f2d1008d3Info)) {
					$zvcqne896e47f047ce9c7c3601cc8bf702f16 = trim_slash($zvcqne896e47f047ce9c7c3601cc8bf702f16);
					$xvapff37a0fb7eb34ffc195e385f2d1008d3Info = preg_replace($gnyf270a8e8d025528086f0dc997facb623, $zvcqne896e47f047ce9c7c3601cc8bf702f16, $xvapff37a0fb7eb34ffc195e385f2d1008d3Info);
					break;
				}
			}
		}
		return $xvapff37a0fb7eb34ffc195e385f2d1008d3Info;
	}
	private static function ynaallnhhnvap9e3f7ca7c1fb0dbd479baa444e9cd011($xvapff37a0fb7eb34ffc195e385f2d1008d3Info) {
		$vxxh0d1f74e489d241f1dd89ebaf49def241 = Config::get('public_app', true);
		if ($xvapff37a0fb7eb34ffc195e385f2d1008d3Info) {
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_info = trim_slash($xvapff37a0fb7eb34ffc195e385f2d1008d3Info);
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_array = explode('/', $xvapff37a0fb7eb34ffc195e385f2d1008d3_info);
			self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array;
			$xvapff37a0fb7eb34ffc195e385f2d1008d3_count = count($xvapff37a0fb7eb34ffc195e385f2d1008d3_array);
			if ($xvapff37a0fb7eb34ffc195e385f2d1008d3_count >= 3) {
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[0];
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['c'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[1];
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['f'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[2];
			} elseif ($xvapff37a0fb7eb34ffc195e385f2d1008d3_count == 2) {
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[0];
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['c'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[1];
			} elseif ($xvapff37a0fb7eb34ffc195e385f2d1008d3_count == 1) {
				$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'] = $xvapff37a0fb7eb34ffc195e385f2d1008d3_array[0];
			}
		}
		if (! isset($vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'])) {
			$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'] = $vxxh0d1f74e489d241f1dd89ebaf49def241[0];
		}
		if (! isset($vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['c'])) {
			$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['c'] = 'Index';
		}
		if (! isset($vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['f'])) {
			$vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['f'] = 'index';
		}
		if (! in_array(strtolower($vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m']), $vxxh0d1f74e489d241f1dd89ebaf49def241)) {
			error('您访问的模块' . $vllnhh_xvap6ed11be5257dc81dff031ea475448fd5['m'] . '未开放,请核对后重试！');
		}
		return $vllnhh_xvap6ed11be5257dc81dff031ea475448fd5;
	}
	private static function gnyaxxnvap74670b424e8b3bda28fd8aa70829fa18($vllnhha77ce475bf24fc09cf0456d082b7787fPath) {
		define('M', strtolower($vllnhha77ce475bf24fc09cf0456d082b7787fPath['m']));
		define('APP_MODEL_PATH', APP_PATH . '/' . M . '/model');
		define('APP_CONTROLLER_PATH', APP_PATH . '/' . M . '/controller');
		if (($axc_fdg49bcb68be76d08a6bc3240e386e6dbd9 = Config::get('tpl_dir')) && array_key_exists(M, $axc_fdg49bcb68be76d08a6bc3240e386e6dbd9)) {
			if (strpos($axc_fdg49bcb68be76d08a6bc3240e386e6dbd9[M], ROOT_PATH) === false) {
				define('APP_VIEW_PATH', ROOT_PATH . $axc_fdg49bcb68be76d08a6bc3240e386e6dbd9[M]);
			} else {
				define('APP_VIEW_PATH', $axc_fdg49bcb68be76d08a6bc3240e386e6dbd9[M]);
			}
		} else {
			define('APP_VIEW_PATH', APP_PATH . '/' . M . '/view');
		}
		if (strpos($vllnhha77ce475bf24fc09cf0456d082b7787fPath['c'], '.') > 0) {
			$cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed = str_replace('.', '/', $vllnhha77ce475bf24fc09cf0456d082b7787fPath['c']);
			$controller = ucfirst(basename($cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed));
			$cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed = dirname($cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed) . '/' . $controller;
		} else {
			$controller = ucfirst($vllnhha77ce475bf24fc09cf0456d082b7787fPath['c']);
			$cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed = $controller;
		}
		$lcvhh_udcn_xvap866e09f713ac5b36d7a6e11417f561fe = APP_CONTROLLER_PATH . '/' . $cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed . 'Controller.php';
		$hvzv_lgsaggccng66ae90d5f7414a598ba4377b22080d5d = array( 'List', 'Content', 'About' );
		$vftqhaa9f2eb07f840c43ade6d43f57d284289 = 0;
		if (M == 'home' && (! file_exists($lcvhh_udcn_xvap866e09f713ac5b36d7a6e11417f561fe) || in_array($controller, $hvzv_lgsaggccng66ae90d5f7414a598ba4377b22080d5d))) {
			$controller = 'Index';
			$cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed = 'Index';
			define('F', $vllnhha77ce475bf24fc09cf0456d082b7787fPath['c']);
			$vftqhaa9f2eb07f840c43ade6d43f57d284289 = - 1;
		} elseif (M == 'home' && in_array($controller, Config::get('second_rvar'))) {
			define('F', 'index');
			define('RVAR', $vllnhha77ce475bf24fc09cf0456d082b7787fPath['f']);
		} else {
			define('F', $vllnhha77ce475bf24fc09cf0456d082b7787fPath['f']);
		}
		define('C', $controller);
		if (isset($_SERVER["REQUEST_URI"])) {
			define('URL', $_SERVER["REQUEST_URI"]);
		} else {
			define('URL', $_SERVER["ORIG_PATH_INFO"] . '?' . $_SERVER["QUERY_STRING"]);
		}
		$xvapff37a0fb7eb34ffc195e385f2d1008d3_count = count(self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array);
		for ($i = 3 + $vftqhaa9f2eb07f840c43ade6d43f57d284289; $i < $xvapff37a0fb7eb34ffc195e385f2d1008d3_count; $i = $i + 2) {
			if (isset(self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array[$i + 1])) {
				$_GET[self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array[$i]] = self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array[$i + 1];
			} else {
				$_GET[self::$xvapff37a0fb7eb34ffc195e385f2d1008d3Array[$i]] = null;
			}
		}
		return $cyh5548ff2cd3d0d94f16fcebe00181da18aggccng_xvap3167fcba5dc1e93cd760e234c32b91ed;
	}
	private static function cgvfcgff7fd1328d9c68d8303390e0b31404dc3a() {
		Config::get('debug') ? Check::checkAppFile() : '';
		if (M == 'api') {
			if (! ! $hdf97a2748eb7b10f9e3d30169e6d5f31b8 = request('sid')) {
				session_id($hdf97a2748eb7b10f9e3d30169e6d5f31b8);
				session_start();
			}
			header("Access-Control-Allow-Origin: *");
		} else {
			Check::checkBs();
			Check::checkOs();
		}
		if (file_exists(APP_PATH . '/common/function.php')) {
			require APP_PATH . '/common/function.php';
		}
		$vxx_lgsudy612a60c6b570bf415470b5a39550d53c = APP_PATH . '/' . M . '/config/config.php';
		if (file_exists($vxx_lgsudy612a60c6b570bf415470b5a39550d53c)) {
			Config::assign($vxx_lgsudy612a60c6b570bf415470b5a39550d53c);
		}
		$vxx_uqsladgs79f92578958902d3f83387b338f90075 = APP_PATH . '/' . M . '/function/function.php';
		if (file_exists($vxx_uqsladgs79f92578958902d3f83387b338f90075)) {
			require $vxx_uqsladgs79f92578958902d3f83387b338f90075;
		}
		if (file_exists(APP_PATH . '/common/' . ucfirst(M) . 'Controller.php')) {
			$lgff_lcvhh_svfn05920fe5dd399e1bc8e27ec55da66477 = '\\app\\common\\' . ucfirst(M) . 'Controller';
			$lgff_lcvhh65b9c886c975fd5ff545bed893829b1b = new $lgff_lcvhh_svfn05920fe5dd399e1bc8e27ec55da66477();
		}
	}
	private static function cgvfcgsaggccng446a06f26641e8be795aafe1c47d924f($controllerPath) {
		$lcvhh_udcn41b78ac5a74bb87c9fdbfaad2c87ca83 = $controllerPath . 'Controller.php';
		$lcvhh_udcn_xvap866e09f713ac5b36d7a6e11417f561fe = APP_CONTROLLER_PATH . '/' . $lcvhh_udcn41b78ac5a74bb87c9fdbfaad2c87ca83;
		$lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c = '\\app\\' . M . '\\controller\\' . str_replace('/', '\\', $controllerPath) . 'Controller';
		$uqsladgs_svfnf65b2c3e2cfd08b43aad42bd7b41df6d = F;
		if (! file_exists($lcvhh_udcn_xvap866e09f713ac5b36d7a6e11417f561fe)) {
			header('HTTP/1.1 404 Not Found');
			header('status: 404 Not Found');
			$udcn_7bec74643b79c55449d8b556a4514593404 = ROOT_PATH . '/404.html';
			if (file_exists($udcn_7bec74643b79c55449d8b556a4514593404)) {
				require $udcn_7bec74643b79c55449d8b556a4514593404;
				exit();
			} else {
				error('对不起，您访问的页面类文件不存在，请核对后再试！');
			}
		}
		if (! class_exists($lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c)) {
			error('类文件中不存在访问的类名！' . $lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c);
		}
		$controller = new $lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c();
		if (method_exists($lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c, $uqsladgs_svfnf65b2c3e2cfd08b43aad42bd7b41df6d)) {
			if (strtolower($lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c) != strtolower($uqsladgs_svfnf65b2c3e2cfd08b43aad42bd7b41df6d)) {
				$gnaqgsde14f8e040ab5b9edaaee8c62895a6dc = $controller->$uqsladgs_svfnf65b2c3e2cfd08b43aad42bd7b41df6d();
			} else {
				$gnaqgsde14f8e040ab5b9edaaee8c62895a6dc = $controller;
			}
		} else {
			if (method_exists($lcvhh_svfn7624dfe4f3aca3b3d2ead10d7e23027c, '_empty')) {
				$gnaqgsde14f8e040ab5b9edaaee8c62895a6dc = $controller->_empty();
			} else {
				error('不存在您调用的类或方法' . $uqsladgs_svfnf65b2c3e2cfd08b43aad42bd7b41df6d . '，可能正在开发中，请耐心等待！');
			}
		}
		if ($gnaqgsde14f8e040ab5b9edaaee8c62895a6dc !== null) {
			print_r($gnaqgsde14f8e040ab5b9edaaee8c62895a6dc);
			exit();
		}
	}
	private static function cgvfcvlpn8229f69ecbdd7a63bfdf94eb205b79a1() {
		if (! Config::get('tpl_html_cache') || URL_BIND == 'api' || get('nocache', 'int') == 1) {
			return;
		}
		$cy_lvlpncee6b079045a4d43646ea9fb03abf3cb = RUN_PATH . '/config/' . md5('area') . '.php';
		if (! file_exists($cy_lvlpncee6b079045a4d43646ea9fb03abf3cb)) {
			return;
		} else {
			Config::assign($cy_lvlpncee6b079045a4d43646ea9fb03abf3cb);
		}
		$cyh5548ff2cd3d0d94f16fcebe00181da18 = Config::get('lgs');
		if (count($cyh5548ff2cd3d0d94f16fcebe00181da18) > 1) {
			$fgfvds317e5dff340248cde5baf42fb8016ca0 = get_http_host();
			foreach ($cyh5548ff2cd3d0d94f16fcebe00181da18 as $zvcqne896e47f047ce9c7c3601cc8bf702f16) {
				if ($zvcqne896e47f047ce9c7c3601cc8bf702f16['domain'] == $fgfvds317e5dff340248cde5baf42fb8016ca0) {
					cookie('lg', $zvcqne896e47f047ce9c7c3601cc8bf702f16['acode']);
				}
			}
		}
		if (! isset($_COOKIE['lg'])) {
			$fnuvqca1ccd426103fab8d0f01580281715a9e7 = current(Config::get('lgs'));
			cookie('lg', $fnuvqca1ccd426103fab8d0f01580281715a9e7['acode']);
		}
		$lgsudy_lvlpneb933b0be7c045e4131760d6f0997c19 = RUN_PATH . '/config/' . md5('config') . '.php';
		if (! Config::assign($lgsudy_lvlpneb933b0be7c045e4131760d6f0997c19)) {
			return;
		}
		if (Config::get('open_wap') && (is_mobile() || Config::get('wap_domain') == get_http_host())) {
			$kvx95865f1adff460e507b6dd5471d4d74c = 'wap';
		} else {
			$kvx95865f1adff460e507b6dd5471d4d74c = '';
		}
		$lvlpn_udcn6c331539381e3ed0ce04793edb2adfe9 = RUN_PATH . '/cache/' . md5(get_http_url() . $_SERVER["REQUEST_URI"] . cookie('lg') . $kvx95865f1adff460e507b6dd5471d4d74c) . '.html';
		if (file_exists($lvlpn_udcn6c331539381e3ed0ce04793edb2adfe9) && time() - filemtime($lvlpn_udcn6c331539381e3ed0ce04793edb2adfe9) < Config::get('tpl_html_cache_time')) {
			ob_start();
			include $lvlpn_udcn6c331539381e3ed0ce04793edb2adfe9;
			$lgsansa9609c00d7e7fa2409e27ad97c2048a39 = ob_get_contents();
			ob_end_clean();
			if (Config::get('gzip') && ! headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
				$lgsansa9609c00d7e7fa2409e27ad97c2048a39 = gzencode($lgsansa9609c00d7e7fa2409e27ad97c2048a39, 6);
				header("Content-Encoding: gzip");
				header("Vary: Accept-Encoding");
				header("Content-Length: " . strlen($lgsansa9609c00d7e7fa2409e27ad97c2048a39));
			}
			echo $lgsansa9609c00d7e7fa2409e27ad97c2048a39;
			exit();
		}
	}
}
?>