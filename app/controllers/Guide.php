<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guide extends MY_Controller
{
	public $script_test_list = array(
		array(
			'label' => "gnb",
			'link' => "/guide/script/gnb",
			'txt' => "Gnb"
		),
		array(
			'label' => "adm_gnb",
			'link' => "/guide/script/adm_gnb",
			'txt' => "ADM - Gnb"
		),
		array(
			'label' => "snb",
			'link' => "/guide/script/snb",
			'txt' => "Snb"
		),
		array(
			'label' => "module.pub",
			'link' => "/guide/script/module.pub",
			'txt' => "eas.pub.js"
		),
		array(
			'label' => "jstree",
			'link' => "/guide/script/jstree",
			'txt' => "JsTree"
		),
	);

	/**
	 * Super
	 */
	function __construct()
	{
		parent::__construct();
		if(ENVIRONMENT == 'production' && _ADMINIP != $_SERVER['REMOTE_ADDR']){
			exit;
		}

		$uri2 = $this->uri->segment(2);
		$uri3 = $this->uri->segment(3);
		$uri4 = $this->uri->segment(4);

		$this->load->library('unit_test');

		// - TEST시 주석 처리
		// - 배포시 주석 해제
		// $this->unit->active(FALSE);

		if ( ! $this->unit->active) // 방어 코드 : TEST모드
		{
			redirect("/approval/doc_list_approval");
		}

		$this->tpl->assign(array(
			'uri2' 				=> $uri2,
			'uri3' 				=> $uri3,
			'uri4' 				=> $uri4,
			'is_test' 			=> $this->unit->active,
			'script_test_list' 	=> $this->script_test_list,
		));

		$this->tpl->define(array(
			'left' 	=> "guide/_snb.html",
			'index' => 'guide/_layout.test.html',
		));
	}

	/**
	 * Main
	 */
	public function index()
	{
		redirect('guide/main');
	}

	public function main()
	{
		$this->tpl->print_('index');
	}

	/**
	 * publishing
	 *
	 * @param $type String - 파일 이름
	 */
	public function publish($type = "plan", $do_id = '')
	{
		if($type === 'backup')
		{
			if ( ! $do_id)
			{
				echo "do_id is empty<br>";
				exit;
			}

			$data = array(
				'do_id' => $do_id,
			);

			$this->load->library('get_view');
			$info = $this->get_view->get_auth(array(
				'do_id' => $data['do_id'],
				'us_id' => $this->session->userdata('sess_id'),
			));
			$info = $this->get_view->get_auth(array(
				'do_id' => $data['do_id'],
				'us_id' => $this->session->userdata('sess_id'),
			));

			$data['sod'] = 'asc';
			$res = $this->get_view->get_view_data($data);

			$closed_status = array(2, 3, 4); // 종결, 종결(전결), 반려
			if ( ! in_array($res["doc_info"]["do_status"], $closed_status)) // 종결 문서는 dummy serial을 대신 보여준다
			{
				$res["doc_info"]["do_serial"] = $this->get_view->get_dummy_serial(array(
					"do_id" => $data["do_id"])
				);
			}

			$opinion = array();
			foreach ( $res["doc_approve_list"] as $key => $val )
			{
				if ( $val["al_opinion"] )
				{
					$opinion[$key]["al_type"] = $val["al_type"];
					$opinion[$key]["us_name"] = $val["us_name"];
					$opinion[$key]["us_title"] = $val["us_title"];
					$opinion[$key]["so_name"] = $val["so_name"];
					$opinion[$key]["al_opinion"] = $val["al_opinion"];
					$opinion[$key]["al_approve_date"] = $val["al_approve_date"];
				}
			}

			$res["opinion"] = $opinion;

			$this->tpl->assign($res);

			$this->tpl->define(array(
				'index' => '_layout.backup.html',
				'contents' => 'guide/publish/print.html',
			));
		}
		else if($type === 'plan' || $type === 'guide' || $type === 'popup' || $type === 'design')
		{
			$this->tpl->define(array(
				'contents' 	=> "guide/publish/_{$type}.html",
				'index' 	=> 'guide/_layout.test.html',
			));
		}
		else {
			$this->tpl->define(array(
				'left' 									=> "eas.left.html",
				'contents' 								=> "guide/publish/{$type}.html",
				'popup_personal_line_submit' 			=> 'popup/personal_line_submit.html',
				'popup_progress_status' 				=> 'popup/progress_status.html',
				'popup_document_move' 					=> 'popup/document_move.html',
				'popup_document_pass' 					=> 'popup/document_pass.html',
				'popup_document_add' 					=> 'popup/document_add.html',
				'popup_inquire_status' 					=> 'popup/inquire_status.html',
				'popup_info' 							=> 'popup/info.html',
				'popup_document_history' 				=> 'popup/document_history.html',
				'popup_opinion_write' 					=> 'popup/opinion_write.html',
				'popup_form_setting_tip' 				=> 'popup/form_setting_tip.html',
				'popup_approval_line_set' 				=> 'popup/approval_line_set.html',
				'popup_approval_line_set1' 				=> 'popup/approval_line_set1.html',
				'popup_approval_line_set1_2' 			=> 'popup/approval_line_set1_2.html',
				'popup_approval_line_set2' 				=> 'popup/approval_line_set2.html',
				'popup_approval_line_set3' 				=> 'popup/approval_line_set3.html',
				'popup_approval_line_set4' 				=> 'popup/approval_line_set4.html',
				'popup_approval_line_set_new1' 			=> 'popup/popup_approval_line_set_new1.html',
				'popup_approval_line_set_new2' 			=> 'popup/popup_approval_line_set_new2.html',
				'popup_approval_line_set_new3' 			=> 'popup/popup_approval_line_set_new3.html',
				'popup_approval_line_set_new4' 			=> 'popup/popup_approval_line_set_new4.html',
				'popup_agreement_receiver_line_set_new' => 'popup/agreement_receiver_line_set_new.html',
				'popup_relied_new' 						=> 'popup/popup_relied_new.html',
				'popup_approval_submit' 				=> 'popup/approval_submit.html',
				'popup_approval' 						=> 'popup/approval.html',
				'popup_agreement' 						=> 'popup/agreement.html',
				'popup_agreement_receiver_line_set' 	=> 'popup/agreement_receiver_line_set.html',
				'popup_personal_changes' 				=> 'popup/personal_changes.html',
				'popup_complete_reception' 				=> 'popup/complete_reception.html',
				'popup_form_storage_select' 			=> "popup/form_storage_select.html",
				'popup_share_results' 					=> 'popup/share_results.html',
				'popup_favorite' 						=> 'popup/favorite.html',
				'popup_favorite_again' 					=> 'popup/favorite_again.html',
				'popup_preview' 						=> 'popup/preview.html',
				'popup_preview_document' 				=> 'popup/preview_document.html',
				'popup_preview_document2' 				=> 'popup/preview_document2.html',
				'popup_comment_write' 					=> 'popup/comment_write.html',
				'popup_adm_regist' 						=> 'popup/adm_regist.html',
				'popup_adm_regist_new' 					=> 'popup/adm_regist_new.html',
				'popup_before_form' 					=> 'popup/before_form.html',
				'popup_relied' 							=> 'popup/relied.html',
				'popup_remote_help' 					=> 'popup/remote_help.html',
				'popup_read_relied' 					=> 'popup/read_relied.html',
				'popup_document_select' 				=> 'popup/document_select.html',
				'popup_vacation_noti' 					=> 'popup/vacation_noti.html',
				'popup_adjust_date' 					=> 'popup/adjust_date.html',
				'popup_vacation1' 						=> 'popup/vacation1.html',
				'popup_vacation2' 						=> 'popup/vacation2.html',
			));

			if($type === 'dashboard')
			{
				$this->tpl->define(array(
					'index' => '_layout.main.html',
				));
			}
			else if($type === 'print_document')
			{
				$this->tpl->define(array(
					'index' => '_layout.normal.html',
				));
			}
			else
			{
				$this->tpl->define(array(
					'index' => '_layout.default.html',
				));
			}
		}

		$this->tpl->print_('index');
	}

	/**
	 * script 단위 테스트
	 */
	public function script($type = "all")
	{
		if ($type === 'jstree')
		{
			$this->load->library('tree_ea_form_storage', array(
				'tree_id' => 'tree-list-id',
			));

			$tree_data = $this->tree_ea_form_storage->get_list();

			$this->tpl->assign(array(
				'tree_data' => $tree_data,
			));
		}

		$this->tpl->define(array(
			'contents' => "guide/script/{$type}.html",
		));

		$this->tpl->print_('index');
	}

	/**
	 * scenario 테스트
	 */
	public function scenario($type = "")
	{
		$data = $this->input->request();

		switch($type)
		{
			case 'create_user': 			// 계정 생성
				$this->sm_create_user();
				break;
			case 'form_list': 				// 양식 선택
				$this->sm_form_list();
				break;
			case 'write': 				// 기안서 작성
				$this->sm_write(array(
					'sess_id' => $data['sess_id'],
					'fo_id' => $data['fo_id'],
					'do_id' => $data['do_id'],
					'do_parent' => $data['do_parent'],
				));
				break;
			case 'doc_list' : 				// 테스트 문서 목록
				$this->sm_doc_list($data);
				break;
			default:
				$this->sm_doc_list($data);
		}

		$this->tpl->define(array(
			'contents' => "guide/scenario/{$type}.html",
		));

		$this->tpl->print_('index');
	}

	/**
	 * texteditor 테스트
	 */
	public function texteditor()
	{
		$this->tpl->print_('index');
	}

	/**
	 * document form 테스트
	 */
	public function documentform()
	{
		$this->load->model('ea_form');
		$this->load->model('ea_form_revision');
		$this->load->model('ea_form_option');

		$display_list = array();

		$query_data = array(
			'order_by' => array(
				'fo_id' => 'ASC',
			),
			'limit' => mt_getrandmax(),
		);
		$f_data = $this->ea_form->find($query_data); // get() 에는 cache 관련 처리가 있어서 find() 를 사용함
		$form_list = (array) $f_data['res'];

		foreach ($form_list as $f_info)
		{
			$f_tmp = array();

			$f_tmp['fo_id'] = $f_info['fo_id'];
			$f_tmp['fo_name'] = $f_info['fo_name'];

			$query_data = array(
				'fo_id' => $f_info['fo_id'],
				'order_by' => array(
					'fr_version' => 'ASC',
					'fr_id' => 'ASC',
				),
			);
			$r_data = $this->ea_form_revision->get($query_data);
			$rev_list = (array) $r_data['res'];

			foreach ($rev_list as &$r_info)
			{
				$query_data = array(
					'fo_id' => $f_info['fo_id'],
					'fr_version' => $r_info['fr_version'],
					'order_by' => array(
						'fp_order' => 'ASC',
						'fp_id' => 'ASC',
					),
				);
				$o_data = $this->ea_form_option->get($query_data);
				$opt_list = $o_data['res'];

				$opt_full_data = array();
				foreach ($opt_list as $idx => $o_info)
				{
					$o_tmp = array(
						'fo_id'       => (string) $o_info['fo_id'],
						'fp_type'     => (string) $o_info['fp_type'],
						'fp_order'    => (string) $o_info['fp_order'],
						'fp_name'     => (string) $o_info['fp_name'],
						'fp_value'    => (string) $o_info['fp_value'],
						'fp_editable' => (string) $o_info['fp_editable'],
					);

					$opt_full_data[] = $o_tmp;
				}
				$json_full_data = json_encode($opt_full_data);
				$opt_hash = sha1($json_full_data);

				$r_info['rev_hash'] = $r_info['fr_hash'];
				$r_info['opt_hash'] = $opt_hash;
				$r_info['integrity'] = (int) ($r_info['rev_hash'] === $r_info['opt_hash']);
				unset($r_info['us_id']);
				unset($r_info['fo_id']);
				unset($r_info['fr_id']);
				unset($r_info['fr_hash']);
				unset($r_info['fr_regdate']);
			}
			unset($r_info);

			$f_tmp['revs'] = $rev_list;

			$display_list[] = $f_tmp;
		}

		$this->tpl->assign('display_list', $display_list);

		$this->tpl->print_('index');
	}

	/**
	 * notify 테스트
	 */
	public function notify($type = "")
	{
		$data = $this->input->request();

		switch($type)
		{
			case 'mail': 			// mail 알림 목록
				$this->nm_mail(array(
					'num' => $data['num'],
				));
				break;
			case 'mail_preview':
				$this->nm_mail_preview(array(
					'num' => $data['num'],
				));
				break;
			default:
				$this->nm_mail(array(
					'num' => $data['num'],
				));
		}

		$this->tpl->define(array(
			'contents' => "guide/notify/{$type}.html",
		));

		$this->tpl->print_('index');
	}

	//-----------------------------------------------------
	// Sub
	//-----------------------------------------------------

	/**
	 * popup test 구동
	 *
	 * @param $file_name String - popup file 명
	 */
	public function popup($file_name = "")
	{
		$tree_prefix = 'treeTest_';
		$tree_id = uniqid($tree_prefix);

		$this->load->library('tree_ea_sosok', array(
			'tree_id' => $tree_id,
		));
		$node_list = $this->tree_ea_sosok->get_list();

		$this->tpl->assign(array(
			'tree_id' => $tree_id,
			'tree_auth' => false,
			'tree_type' => 'tree_ea_sosok',
			'tree_list' => $node_list,
		));

		$this->tpl->define(array(
			'tree_list' => JSTREE_DIR.'/tree_list.html',
			'index' => "popup/{$file_name}.html",
		));

		$this->tpl->print_('index');
	}

	/**
	 * mark_up 가이드 라인
	 */
	public function mark_up($type = "1")
	{
		$this->tpl->define(array(
			'contents' 	=> "guide/mark_up/{$type}.html",
			'index' 	=> 'guide/_layout.test.html',
		));

		$this->tpl->print_('index');
	}


	public function ci_guide($lang = 'kr')
	{
		$this->tpl->define(array(
			'contents' 	=> "guide/ci_guide/{$lang}/styleguide.html",
			'index' 	=> 'guide/_layout.test.html',
		));

		$this->tpl->print_('index');
	}

	public function python_guide($type='common')
	{
		$this->tpl->define(array(
			'contents' 	=> "guide/python/{$type}.html",
			'index' 	=> 'guide/_layout.test.html',
		));

		$this->tpl->print_('index');
	}

	//-----------------------------------------------------
	// Sub - Notify Module
	//-----------------------------------------------------

	/**
	 * [Notify Module] 메일 발송
	 *
	 * Copy 형태를 남겨두기 위해 공통코드 분리하지 않음
	 */
	public function send_mail()
	{
		$data = $this->input->request();

		$sess_id = $this->session->userdata('sess_id');
		$user = $this->ea_users->user_get($sess_id);
		$do_id = "문서 번호";
		$res = array();

		if ($data['num'] == 1)
		{
			// (사용 X) 대결 문서 권한 해제 알림 (notify_form, 1, original)
			notify_mail(array(
				'num' => 1,
				'repl_arr' => array(
					'{위임자}' => "대결 요청자",
					'{대결해제일}' => "대결 요청 해제일",
					'{전자결재_바로가기}' => "/dashboard/",
				),
				// 'to_account' => "대결자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 2)
		{
			// 대결 문서 승인 요청 알림 (notify_form, 2, deputy)
			notify_mail(array(
				'num' => 2,
				'repl_arr' => array(
					'{문서제목}' => "문서 제목",
					'{기안자}' => "기안자",
					'{기안일}' => "기안일",
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=approval",
				),
				// 'to_account' => "대결자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 3)
		{
			// 공람자 알림 (notify_form, 3, display)
			notify_mail(array(
				'num' => 3,
				'repl_arr' => array(
					'{문서제목}' => "문서 제목",
					'{기안자}' => "기안자",
					'{기안일}' => "기안일",
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=exposed",
				),
				// 'to_account' => "공람자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 4)
		{
			// 열람자 알림 (notify_form, 4, reading)
			notify_mail(array(
				'num' => 4,
				'repl_arr' => array(
					'{문서제목}' => "문서 제목",
					'{기안자}' => "기안자",
					'{기안일}' => "기안일",
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=exposed",
				),
				// 'to_account' => "열람자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 5)
		{
			// 대결 조기 해지 알림 (notify_form, 5, ear_clear)
			notify_mail(array(
				'num' => 5,
				'repl_arr' => array(
					'{위임자}' => "대결 요청자",
					'{결재문서_바로가기}' => "/dashboard/",
				),
				// 'to_account' => "대결자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 6)
		{
			// (사용 X) 대결 해지 알림 (notify_form, 6, del_clear)
			notify_mail(array(
				'num' => 6,
				'repl_arr' => array(
					'{위임자}' => "대결 요청자",
					'{대결해제일}' => "대결 하는 사람이 해제한 날짜",
					'{전자결재_바로가기}' => "/dashboard/",
				),
				// 'to_account' => "대결자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 7)
		{
			// 대결 설정 알림 (notify_form, 7, delegate)
			notify_mail(array(
				'num' => 7,
				'repl_arr' => array(
					'{위임자}' => "대결 요청자",
					'{대결기간}' => "유효 대결 기간",
					'{대결사유}' => "해당 대결 사유",
					'{전자결재_바로가기}' => "/dashboard/",
				),
				// 'to_account' => "대결자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 8)
		{
			// 결재 요청 알림 (notify_form, 8, appr)
			notify_mail(array(
				'num' => 8,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{기안자}' => "기안자",
					'{기안일}' => "기안일",
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=approval",
				),
				// 'to_account' => "다음 배정자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 9)
		{
			// 기안부서에 결과 공유 알림 (notify_form, 9, share)
			notify_mail(array(
				'num' => 9,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{문서상태}' => "종결 or 반려", // 종결(전결) -> 종결
					'{최종결재자}' => "마지막 결재자",
					'{결재일}' => "종결일",
					'{부서내의견}' => "마지막 결재자 의견", // case by empty -> '-'
					'{결재문서_바로가기}' => "/draft/doc_view/request?do_id=" . $do_id,
				),
				// 'to_account' => "기안자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 10)
		{
			// 결과 과정 중 반려 알림 (notify_form, 10, return_appr), To 나머지
			notify_mail(array(
				'num' => 10,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{반려자}' => "마지막 결재자",
					'{반려일}' => "종결일",
					'{반려의견}' => "마지막 결재자 의견", // case by empty -> '-'
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=closed",
				),
				// 'to_account' => "기안자 제외한 결재자(승인, 후열(확인)) + 열람자(공람자 제외)",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 11)
		{
			// 결과 과정 중 반려 알림 (notify_form, 11, return_draft), To 기안자
			notify_mail(array(
				'num' => 11,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{반려자}' => "마지막 결재자",
					'{반려일}' => "종결일",
					'{반려의견}' => "마지막 결재자 의견", // case by empty -> '-'
					'{결재문서_바로가기}' => "/draft/doc_view/request?do_id=" . $do_id,
				),
				// 'to_account' => "기안자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 12)
		{
			// 결재 완료 알림 (notify_form, 12, last_appr), To 기안자
			notify_mail(array(
				'num' => 12,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{최종결재자}' => "마지막 결재자",
					'{결재일}' => "마지막 결재일",
					'{결재의견}' => "마지막 결재자 의견", // case by empty -> '-'
					'{결재문서_바로가기}' => "/draft/doc_view/request?do_id=" . $do_id,
				),
				// 'to_account' => "기안자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}
		else if ($data['num'] == 13)
		{
			// 결재 완료 알림 (notify_form, 13, appr_share), To 결재자
			notify_mail(array(
				'num' => 13,
				'repl_arr' => array(
					'{문서종류}' => "양식명",
					'{문서제목}' => "문서 제목",
					'{기안자}' => "기안자",
					'{기안일}' => "기안일",
					'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $do_id . "&doc_type=approval",
				),
				// 'to_account' => "기안자 제외한 결재자",
				'to_account' => $user['us_account'],
			));

			// set res
			$res['code'] = 200;
			$res['data']['send_to'] = $user['us_account']."@"._DOMAIN;
		}

		echo json_encode($res);
	}

	/**
	 * [Notify Module] 메일
	 */
	public function nm_mail($data = array())
	{
		$form_list = get_code_list('notify_form');
		$mail_list = array();
		$not_use_list = array(1, 6);
		$title_list = array(
			1 => "대결 문서 권한 해제 알림",
			2 => "대결 문서 승인 요청 알림",
			3 => "공람자 알림",
			4 => "열람자 알림",
			5 => "대결 조기 해지 알림",
			6 => "대결 해지 알림",
			7 => "대결 설정 알림",
			8 => "결재 요청 알림",
			9 => "기안부서에 결과 공유 알림",
			10 => "결과 과정 중 반려 알림",
			11 => "결과 과정 중 반려 알림",
			12 => "결재 완료 알림",
			13 => "결재 완료 알림",
		);

		foreach((array)$form_list as $key => $form)
		{
			$form_tmp = explode("|", $form);
			$subject = $form_tmp[0];
			$form_name = $form_tmp[1];

			$mail_list[] = array(
				'use' => (in_array($key, $not_use_list)) ? FALSE : TRUE,
				'title' => $title_list[$key],
				'num' => $key,
				'subject' => $subject,
				'form_name' => $form_name,
			);
		}

		$this->tpl->assign(array(
			'mail_list' => $mail_list,
		));
	}

	/**
	 * [Notify Module] 메일 미리보기
	 */
	public function nm_mail_preview($data = array())
	{
		if ($data['num'])
		{
			// 알림 정보
			$notify_tmp = get_code_name('notify_form', $data['num']);
			$notify_data = explode("|", $notify_tmp);

			$this->tpl->define('index', "mail_form_" . $notify_data[1] . ".html");
		}
	}
	//-----------------------------------------------------
	// Sub - Scenario Module
	//-----------------------------------------------------

	/**
	 * [Scenario Module] 문서 목록
	 */
	public function sm_doc_list($data = array())
	{
		$this->load->model("ea_document");

		$sess_id = $this->session->userdata('sess_id');

		// Filter : 문서 상태
		$doc_status_filter = array();
		foreach((array)get_code_list('doc_status') as $key => $status)
		{
			if ($key != 7)
			{
				$doc_status_filter[] = array(
					'do_status' => $key,
					'txt' 		=> $status,
					'is_check' 	=> ($data['do_status']) ? in_array($key, $data['do_status']) : FALSE,
				);
			}
		}

		// Paging Library
		$this->load->library('pagination');
		$this->pagination->base_url = $this->input->server('PHP_SELF')."?".query_string('page');
		$this->pagination->per_page = $this->input->request('s_rows', FALSE, 20);

		$list_res = $this->ea_document->get_doc_list(array(
			// 'us_id' => $sess_id,
			'do_status' => $data['do_status'],
			'fo_id' => $data['fo_id'],
			'limit' => $s_rows,
			'sst'       => ($data['sst']) ? $data['sst'] : "dc.do_id",
			'sod'       => ($data['sod']) ? $data['sod'] : "DESC",
		), TRUE);

		// 문서 정보 있고,
		if (isset($data['do_id']))
		{
			// 사용자 정보 있으면
			if (isset($data['sess_id']))
			{
				$this->sm_menu_info(array(
					'do_id' => $data['do_id'],
					'sess_id' => $data['sess_id'],
				));
			}

			// 기안자 정보 있으면
			if (isset($data['draft_id']))
			{
				$this->sm_doc_info(array(
					'do_id' => $data['do_id'],
					'draft_id' => $data['draft_id'],
					'sess_id' => $data['sess_id'],
				));
			}
		}

		foreach((array)$list_res['res'] as $key => $parent_doc)
		{
			$sub_doc_list = $this->ea_document->get_doc_list(array(
				'do_parent' => $parent_doc['do_id'],
				'sst'       => ($data['sst']) ? $data['sst'] : "dc.do_draft_date",
				'sod'       => ($data['sod']) ? $data['sod'] : "DESC",
			));

			$list_res['res'][$key]['sub_doc_list'] = $sub_doc_list;
		}

		$this->tpl->assign(array(
			'deco' => $list_res["deco"],
			'items' => $list_res["res"],
			's_rows' => $s_rows,
			'doc_status_filter' => $doc_status_filter,
			'do_status' => $data['do_status'],
			'sess_id' => $data['sess_id'],
		));
	}

	/**
	 * [Scenario Module] 메뉴 정보
	 */
	public function sm_menu_info($data = array())
	{
		$this->load->library('get_view');

		$menu = $this->get_view->get_auth(array(
			'do_id' => $data['do_id'],
			'us_id' => $data['sess_id'],
		));

		// 소속 정보
		$sosok = $this->ea_sosok->get_one(array(
			'so_id' => $menu['user']['so_id'],
		));

		$this->tpl->assign(array(
			'menu' => $menu,
			'sosok' => $sosok,
		));
	}

	/**
	 * [Scenario Module] 문서 정보
	 */
	public function sm_doc_info($data = array())
	{
		$this->load->model('ea_document');
		$this->load->model('ea_approve_line');

		$this->load->library('get_view');
		$this->load->library('liner');

		$doc = $this->ea_document->get_doc_info(array( // 문서 정보
			'do_id' => $data['do_id'],
		));

		// view data 가져오기 및 이력
		if (isset($data['sess_id']))
		{
			$view_data = $this->get_view->get_view_data(array(
				'do_id' => $doc['do_id'],
				'us_id' => $data['sess_id'],
			));
		}

		$line = $this->liner->get_al($doc['do_id']);
		$inline = $this->liner->convert_multi(array(
			'approve_list' => $line['turn_base']['inline'],
			'do_status' => $doc['do_status'],
			'agree_multi' => $line['is_multi']['agree'],
			'receive_multi' => $line['is_multi']['receive'],
		));
		$outline = $line['turn_base']['outline'];

		foreach((array)$inline as $key => $this_line)
		{
			$user_doc = $this->ea_approve_line->get_doc_info(array(
				'do_id' => $data['do_id'],
				'sess_id' => $this_line['us_id'],
				'us_id' => $this_line['us_id'],
			));

			$inline[$key]['is_read'] = ($user_doc['ih_read_date']) ? TRUE : FALSE;

			if ($this_line['us_sub_id'])
			{
				$sub_user_doc = $this->ea_approve_line->get_doc_info(array(
					'do_id' => $data['do_id'],
					'sess_id' => $this_line['us_sub_id'],
					'us_sub_id' => $this_line['us_sub_id'],
				));

				$inline[$key]['is_sub_read'] = ($sub_user_doc['ih_read_date']) ? TRUE : FALSE;
			}
		}

		foreach((array)$outline as $key => $this_line)
		{
			$user_doc = $this->ea_approve_line->get_doc_info(array(
				'do_id' => $data['do_id'],
				'sess_id' => $this_line['us_id'],
				'us_id' => $this_line['us_id'],
			));

			$outline[$key]['is_read'] = ($user_doc['ih_read_date']) ? TRUE : FALSE;

			if ($this_line['us_sub_id'])
			{
				$sub_user_doc = $this->ea_approve_line->get_doc_info(array(
					'do_id' => $data['do_id'],
					'sess_id' => $this_line['us_sub_id'],
					'us_sub_id' => $this_line['us_sub_id'],
				));

				$outline[$key]['is_sub_read'] = ($sub_user_doc['ih_read_date']) ? TRUE : FALSE;
			}
		}

		// read_date 상대체크
		$list_res['res'][$key]['ih_read_date'] = $this_doc['ih_read_date'];

		$info = $this->get_view->get_auth(array(
			'do_id' => $doc['do_id'],
			'us_id' => $data['draft_id'],
		));

		$this->tpl->assign(array(
			'info' => $info,
			'inline' => $inline,
			'outline' => $outline,
		));
	}

	/**
	 * [Scenario Module] 양식 선택
	 */
	public function sm_form_list($data = array())
	{
		$this->load->model('ea_form');
		$get_form_list = $this->ea_form->find(array());
		$form_list = $get_form_list['res'];

		$recent_used = $this->ea_form->get_recent_used_list();

		$this->tpl->assign(array(
			'sess_id' =>$this->session->userdata('sess_id'),
			'form_list' => $form_list,
			'recent_used' => $recent_used,
		));
	}

	/**
	 * [Scenario Module] 상신 타입 얻어오기
	 */
	public function get_draft_info($data = array())
	{
		$draft = array();

		$draft['type'] = "draft";
		if ($data['do_id']) { // 문서 정보 있으면
			$draft['type'] = "redraft"; // 임시저장의 기안도 재기안
		}

		$draft['doc_type'] = "origin";
		if ($data['do_parent']) // 부모 정보 있으면
		{
			$draft['doc_type'] = "department";
		}
		$draft['action'] = $draft['type'] . "_" . $draft['doc_type'];

		return $draft;
	}

	/**
	 * [Scenario Module] 기안서 작성
	 */
	public function sm_write($data = array())
	{
		$this->load->library('quota_calculator');
		$this->load->library('formatter');
		$this->load->library('get_view');
		$this->load->library('liner');
		$this->load->model('ea_sosok');
		$this->load->model('ea_users');
		$this->load->model('ea_document_file');
		$this->load->model('ea_personal_line');

		$us_id = $this->session->userdata('sess_id');
		$return_url = "/guide/scenario/form_list";
		if ($data['sess_id'])
		{
			$user = $this->ea_users->user_get($data['sess_id']);
			$us_id = $user['us_id'];
		}

		// get draft info
		$draft = $this->get_draft_info(array(
			'do_id' => $data['do_id'],
			'do_parent' => $data['do_parent'],
		));

		// check 용량계산
		$writable = $this->quota_calculator->do_check_size();
		if ( ! $writable)
		{
			$this->session->set_flashdata('errors', "사용량을 초과하셨습니다.");
			redirect($return_url);
		}

		// check required param fo_id
		if ( ! $data['fo_id'])
		{
			$this->session->set_flashdata('errors', "양식을 선택해주세요.");
			redirect($return_url);
		}

		$form_data = $this->formatter->get_form($data['fo_id']);
		// check : form
		if ( ! $form_data['fo_id'])
		{
			$this->session->set_flashdata('errors', "현재 작성 불가한 양식입니다.");
			redirect($return_url);
		}

		// check : form use
		if ( ! $form_data['fo_use'])
		{
			$this->session->set_flashdata('errors', "미사용 전환된 양식으로 작성이 불가합니다.");
			redirect($return_url);
		}

		// check : 부서내 새결재 기안시
		if ($draft['action'] === "draft_department")
		{
			$info = $this->get_view->get_auth(array(
				'do_id' => $data['do_parent'],
				'us_id' => $us_id,
			));

			if ( ! $info['auth']['branch_draft']) {
				$this->session->set_flashdata('errors', "해당 문서에 대한 권한이 없습니다.");
				redirect($return_url);
			}
		}

		// SET OPTIONS
		foreach ($form_data['revision']['latest']['option']['format_content'] as $idx => &$row)
		{
			$row['opt_id'] = "draft_opt_{$idx}";
		}

		// SET Approve Line
		if ($draft['type'] === "draft") // 기안시 양식 기본 결재선
		{
			$line_data = $this->liner->get_dl($data['fo_id']);

			// 부서내 결재 문서 기안시
			if ($draft['action'] === "draft_department") // 부모 문서 정보 상속
			{
				$res = $this->get_view->get_view_data(array(
					'do_id' => $data['do_parent'],
				));
			}
		}
		else // 재기안시
		{
			$res = $this->get_view->get_view_data(array(
				'do_id' => $data['do_id'],
			));

			$line_data = $this->liner->get_al($data['do_id']);

			$apply_multi = array(
				'agree' => (get_code_name('ea_config', 'approval_agree_multi') == "Y") ? TRUE : FALSE,
				'receive' => (get_code_name('ea_config', 'approval_receive_multi') == "Y") ? TRUE : FALSE,
			);
			if ((( ! $apply_multi['agree']) && $line_data['is_multi']['agree']) && (( ! $apply_multi['receive']) && $line_data['is_multi']['receive'])) { // case by 합의/수신 병렬 사용 OFF시 합의/수신 병렬선 존재
				// alert : 사내 병렬 사용 설정이 제한되었습니다.
				$this->tpl->assign('errors', langs('lang_make_draft','157'));
			}
			else if (( ! $apply_multi['agree']) && $line_data['is_multi']['agree']) { // case by 합의 병렬 사용 OFF시 합의 병렬선 존재
				// alert : 사내 병렬 합의 사용 설정이 제한되었습니다.
				$this->tpl->assign('errors', langs('lang_make_draft','155'));
			}
			else if (( ! $apply_multi['receive']) && $line_data['is_multi']['receive']) { // case by 수신 병렬 사용 OFF시 수신 병렬선 존재
				// alert : 사내 병렬 수신 사용 설정이 제한되었습니다.
				$this->tpl->assign('errors', langs('lang_make_draft','156'));
			}

			$doc_approve_list = $this->liner->convert_multi(array(
				'approve_list' => $line_data['turn_base']['inline'],
				'agree_multi' => $apply_multi['agree'],
				'receive_multi' => $apply_multi['receive'],
			));
			$line_data['turn_base']['inline'] = $doc_approve_list;

			$doc_display_list = $line_data['type_base'][4]['combination_list'];
			$doc_reading_list = $line_data['type_base'][5]['combination_list'];

			$this->tpl->assign(array(
				'doc_approve_list' => $line_data['turn_base']['inline'],
				'doc_display_list' => $doc_display_list,
				'doc_reading_list' => $doc_reading_list,
			));
		}

		// 직속 부서의 문서철만 가져오기
		$get_doc_files = $this->ea_document_file->get_file_list(array(
			'so_id' => ($data['sess_id']) ? $user['so_id'] : get_code_name('user','so_id'),
		));
		$doc_file_multi = (count($get_doc_files) > 1) ? TRUE : FALSE;
		$doc_file_list = array(); 	// 문서철 선택 select
		$doc_file = array(); 		// 문서철 선택 span
		if ($doc_file_multi)
		{
			foreach((array)$get_doc_files as $key => $file) {
				$doc_file_list[$file['ds_id']]['storage'] = $file['ds_name'];
				// ds_id가 가장 작은 파일이 공용문서철이라고 판단
				if ( $key == 0) {
					$file['is_default'] = true;
				}
				$doc_file_list[$file['ds_id']]['files'][$key] = $file;
			}
		}
		else
		{
			$doc_file = $get_doc_files[0];
		}

		// SET 개인 결재선 정보
		$pl_data = $this->ea_personal_line->get(array(
			'us_id' => $us_id,
			'order_by' => array(
				'pl_regdate' => 'DESC', // EAS-194 최근 등록순
			),
		));

		$this->tpl->assign(array(
			'draft' => $draft,
			'fo_id' => $data['fo_id'],
			'do_id' => $data['do_id'],
			'do_parent' => $data['do_parent'],
			'form_data' => $form_data,
			'line_data' => $line_data,
			'doc_info' => $res["doc_info"],
			'pl_data' => json_encode($pl_data['res']),
			'doc_file_multi' => $doc_file_multi,
			'doc_file_list' => $doc_file_list,
			'doc_file' => $doc_file,
		));

		$this->tpl->define(array(
			'popup_approval_submit' => 'popup/approval_submit.html',
			'file_upload' => 'file_upload_html5_2.html',
		));
	}

	/**
	 * [Module] 상신
	 *
	 * /make_draft/draft_apply
	 */
	public function do_draft()
	{
		$data = $this->input->request();

		$this->load->library('formatter');
		$this->load->library('sosok_user');
		$this->load->library('els_search');
		$this->load->library('quota_calculator');
		$this->load->library('get_view');

		$this->load->model('ea_approve_line');
		$this->load->model('ea_approve_history');
		$this->load->model('ea_substitute');
		$this->load->model('ea_users');
		$this->load->model('ea_sosok');
		$this->load->model('ea_document');
		$this->load->model('ea_attach');

		$cached_time = date('Y-m-d H:i:s'); // 방어코드 : 현재 시점 cache (오차)

		// get draft info
		$draft = $this->get_draft_info(array(
			'do_id' => $data['do_id'],
			'do_parent' => $data['do_parent'],
		));

		$return_url = '/guide/scenario/form_list';

		// set data
		$al_user_arr = (array)$data['al_user']; 							// 결재자 리스트
		$do_status = ($data['do_status'] == 6) ? $data['do_status'] : 1; 	// default 1 : 진행중

		$form_data = $this->formatter->get_form($data['fo_id']);

		// check : form use : 미사용이면 임시저장 로직 태움
		if ( ! $form_data['fo_use'])
		{
			$do_status = 6;
		}

		// check : editor 사용 여부
		$do_editor = $data['do_editor'];
		if ( ! $form_data['fo_editor_use'])
		{
			$do_editor = '';
		}

		$us_id = $this->session->userdata('sess_id');
		if ($data['sess_id']) {
			$us_id = $data['sess_id'];
		}
		$user_data = $this->ea_users->user_get($us_id);
		$sosok_data = $this->ea_sosok->get_one(array(
			'so_id' => get_code_name('user','so_id'),
		));

		// 권한 얻어오기
		$info = $this->get_view->get_auth(array(
			'do_id' => $data['do_parent'],
			'us_id' => $us_id,
		));

		// check : 부서내 새결재 문서 상신 권한
		if ($draft['doc_type'] == 'department')
		{
			if (! $info['auth']['branch_draft'])
			{
				echo $us_id;
				p_r($info['auth']);
				exit;
				$this->session->set_flashdata('errors', "해당 문서에 대한 권한이 없습니다.");
				redirect($return_url);
			}
		}

		// SET option content
		$do_option_content = "";
		if ($form_data['fo_option_use'])
		{
			foreach ($form_data['revision']['latest']['option']['format_content'] as $idx => $row)
			{
				$input_id = "draft_opt_" . $idx;
				$draft_option = $data[$input_id];

				$do_option_content .= $this->formatter->build_option_content($input_id, $draft_option, $row['fp_type'], $row['fp_name'], $row['fp_value']);
			}
		}

		$query_data = array(
			'fo_id'             => $data['fo_id'],
			'fo_name'           => $form_data['fo_name'],
			'df_id'             => $data['df_id'],
			'us_id'             => $us_id,
			'us_name'           => $user_data['us_name'],
			'us_title'          => $user_data['us_title'],
			'so_name'           => $sosok_data['so_name'],
			'do_title'          => $data['do_title'],
			'do_parent'         => $data['do_parent'],
			'do_content'        => $do_editor,
			'do_option_content' => $do_option_content,
			'do_status'         => $do_status,
			'do_approve_type'   => $form_data['fo_approve_type'],
			'do_preserve'       => $form_data['fo_preserve'],
			'do_draft_date'     => date2time($cached_time),
			'do_img_count'      => '0',			// 임시지정
			'do_attach_count'   => '0',
			'do_public'         => $data['do_public'],
			'do_hash'           => $form_data['revision']['latest']['fr_hash'],
		);

		// SET Document
		$query_data['do_content'] = get_base64_convert($query_data['do_content'], "encode");
		$query_data['do_option_content'] = get_base64_convert($query_data['do_option_content'], "encode");
		$set_doc = $this->ea_document->insert($query_data);
		$set_do_id = $set_doc['return_id'];

		// SET ELS
		$query_data['do_id'] = $set_do_id;
		$query_data['do_content'] = get_base64_convert($query_data['do_content'], "decode");
		$query_data['do_option_content'] = get_base64_convert($query_data['do_option_content'], "decode");
		$query_data['at_file'] = $data['newfilename_name'];
		$query_data['so_id'] = $this->sosok_user->get_dept_list();

		$els_data = $this->els_search->do_els_insert($query_data, $al_user_arr);
		unset($query_data['at_file']);
		unset($query_data['so_id']);

		// SET 기안자 결재선 지정
		$query_data = array(
			'do_id' => $set_do_id,
			'us_id' => $us_id,
			'us_name' => $user_data['us_name'],
			'us_title' => $user_data['us_title'],
			'so_name' => $sosok_data['so_name'],
			'al_turn' => 1,
			'al_type' => 6,
		);
		// 임시저장이면 결재선 갱신 로직 X
		if ($do_status != 6)
		{
			$query_data['al_start_date'] = date2time($cached_time);
			$query_data['al_approve_date'] = date2time($cached_time);
			$query_data['al_status'] = 1;
			$query_data['al_opinion'] = $data['ah_opinion'];
		}
		$this->ea_approve_line->insert($query_data);

		// 문서 의견 count 갱신
		if (trim($data['ah_opinion']) !== '')
		{
			$this->ea_document->update_doc_info(array(
				'do_id' => $set_do_id,
				'ah_opinion' => TRUE,
			));
		}

		$doc = $this->ea_document->get_doc_info(array(
			'do_id' => $set_do_id,
		));

		// SET 나머지 결재선 지정
		$first_turn = 0;
		$ap_us_id = "";
		$ap_users = "";
		foreach ($al_user_arr as $json)
		{
			$substitute_set_flag = FALSE;
			$line_user = json_decode($json, TRUE);

			$query_data = array(
				'do_id' => $set_do_id,
				'us_id' => $line_user['us_id'],
				'us_name' => $line_user['us_name'],
				'us_title' => $line_user['us_title'],
				'so_name' => $line_user['so_name'],
				'al_turn' => $line_user['turn'],
				'al_type' => $line_user['type'],
			);

			// 임시저장이면 결재선 갱신 로직 X
			if ($do_status != 6)
			{
				// 첫번재 턴 입력
				if ( ! $first_turn)
				{
					$first_turn = $line_user['turn'];
				}

				// 갱신 : 첫 결재자
				if ($line_user['turn'] == $first_turn)
				{
					// 배정자 누적
					if (($ap_us_id !== "") ||($ap_users !== ""))
					{
						$ap_us_id .= "|";
						$ap_users .= "|";
					}
					$ap_us_id .= $line_user['us_id'];
					$ap_users .= $line_user['us_name'] . " " . $line_user['us_title'];

					$query_data['al_start_date'] = date2time($cached_time); 					// 도착시간 갱신
					$user = $this->ea_users->user_get($line_user['us_id']);
					notify_mail(array(
						'num' => 8,
						'repl_arr' => array(
							'{문서종류}' => $doc['fo_name'],
							'{문서제목}' => $doc['do_title'],
							'{기안자}' => $doc['us_name'],
							'{기안일}' => date('Y-m-d', $doc['do_draft_date']),
							'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $doc['do_id'] . "&doc_type=approval",
						),
						'to_account' => $user['us_account'],
					));

					// 유효 대결 정보 가져오기
					$substitute_info = $this->ea_substitute->get_substitute_info(array(
						'us_id' => $line_user['us_id'],
						'su_use' => 1,
						'su_valid_date' => date2time($cached_time),
					));

					if ($substitute_info['su_id']) // 유효 대결 정보 있으면 대결자 정보 갱신
					{
						$substitute_set_flag = TRUE;
						$query_data['us_sub_id'] = $substitute_info['us_sub_id'];
						$query_data['us_sub_name'] = $substitute_info['us_sub_name'];
						$query_data['us_sub_title'] = $substitute_info['us_sub_title'];
					}

					$this->ea_document->update_doc_info(array( // 배정자 마킹
						'do_id' => $set_do_id,
						'ap_us_id' => $ap_us_id,
						'ap_users' => $ap_users,
						'ap_start_date' => date2time($cached_time),
					));
				}

				// 갱신 : 열람자
				if ($line_user['type'] == '4')
				{
					$query_data['al_start_date'] = date2time($cached_time); 	// 도착시간 갱신
					// 열람자 알림 (notify_form, 4, reading)
					$user = $this->ea_users->user_get($line_user['us_id']);
					notify_mail(array(
						'num' => 4,
						'repl_arr' => array(
							'{문서제목}' => $doc['do_title'],
							'{기안자}' => $doc['us_name'],
							'{기안일}' => date('Y-m-d', $doc['do_draft_date']),
							'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $doc['do_id'] . "&doc_type=exposed",
						),
						'to_account' => $user['us_account'],
					));
				}
			}

			if ($line_user['type'] == '4' OR $line_user['type'] == '5')
			{
				$query_data['al_turn'] = '0';
			}

			$this->ea_approve_line->insert($query_data);

			// 대결에 대한 알림 Histroy
			if ($substitute_set_flag)
			{
				// history notify : 대결 지정 알림 (주체자 기록 여부 Y, 추가의견 가능 여부 N)
				$res = $this->ea_approve_history->insert_approve_history(array(
					'do_id' => $set_do_id,
					'ah_type' => 5, // 대결자 지정
					'ah_msg' => $line_user['us_name'] . " " . $line_user['us_title'] . " → " . $substitute_info['us_sub_name'] . " " . $substitute_info['us_sub_title'],
				));

				if ($res) // 의견 카운트 갱신
				{
					$this->ea_document->update_doc_info(array(
						'do_id'      => $data['do_id'],
						'ah_opinion' => TRUE,
					));
				}

				// 대결 문서 승인 요청 알림 (notify_form, 2, deputy)
				$user = $this->ea_users->user_get($substitute_info['us_sub_id']);
				notify_mail(array(
					'num' => 2,
					'repl_arr' => array(
						'{문서제목}' => $doc['do_title'],
						'{기안자}' => $doc['us_name'],
						'{기안일}' => date('Y-m-d', $doc['do_draft_date']),
						'{결재문서_바로가기}' => "/approval/doc_view?do_id=" . $doc['do_id'] . "&doc_type=approval",
					),
					'to_account' => $user['us_account'],
				));
			}
		}

		// 임시저장이면 파일 첨부 및 결재선 갱신 로직 X
		if ($do_status != 6)
		{
			// 부서내 새결재 기안시 부모 문서에 결재선 '부서내 결재중'으로 갱신
			if ($draft['action'] == 'draft_department')
			{
				$my_approve_data = $this->ea_approve_line->get(array(
					'do_id' => $data['do_parent'],
					'us_id' => $us_id,
					'al_start_date' => '0',
					'al_approve_date' => '-1',
					'al_type' => array('2','3'),
					'al_status' => '0',
				));
				$my_approve_data = $my_approve_data['res'][0];
				if ($my_approve_data['al_id']) {
					$this->ea_approve_line->update(array(
						'al_id' => $my_approve_data['al_id'],
						'al_status' => '8',
					));
				}
			}

			// SET 첨부파일
			$do_attach_count = 0;

			$pre_uploaded = (array) $this->input->post('at_id');
			$pre_uploaded_count = count($pre_uploaded);
			foreach ($pre_uploaded as $idx => $val)
			{
				$attach_data = $this->ea_attach->get(array(
					'at_id' => $val,
				));
				$attach_data = $attach_data['res'][0];

				$this->ea_attach->insert(array(
					'do_id' => $set_do_id,
					'at_order' => $attach_data['at_order'],
					'at_origin' => $attach_data['at_origin'],
					'at_real_name' => $attach_data['at_real_name'],
					'at_saved_name' => $attach_data['at_saved_name'],
					'at_file_size' => $attach_data['at_file_size'],
					'at_file_type' => $attach_data['at_file_type'],
				));

				++$do_attach_count;
			}

			$key = (string)$this->config->item('encryption_key');

			$tmpname_arr = $data['newfilename'];
			$realname_arr = $data['newfilename_name'];
			$fsize_arr = $data['newfilename_size'];
			$ftype_arr = $data['newfilename_type'];
			if ( ! empty($tmpname_arr))
			{
				$attach_sum = 0;
				$eas_path = _EAS_ATTACHPATH.date('Ym').'/';
				if(!is_dir($eas_path)){
					@mkdir($eas_path,0755);
					@chown($eas_path, "nobody");
					@chgrp($eas_path, "nobody");
				}
				$upload_count = count($tmpname_arr);

				for ($at_idx = 0; $at_idx < $upload_count; ++$at_idx)
				{
					unset($tmp_file);
					$tmp_path = _TMPDOMAIN."{$tmpname_arr[$at_idx]}";

					$key = (string)$this->config->item('encryption_key');

					$tmp_file = file_get_contents(_TMPDOMAIN."{$tmpname_arr[$at_idx]}");
					$tmp_file = get_base64_convert($tmp_file, "encode");

					$time = time2date('dhis', time());
					$uniqid = md5(uniqid(''));
					$fname = $time."_".$uniqid;

					$eas_file = "{$eas_path}{$fname}";

					if ( ! file_exists($eas_file))
					{
						file_put_contents( $eas_file, $tmp_file, FILE_APPEND );
					}

					/**
					 * 첨부파일 검증
					 * 파일이 존재하지 않거나, 실제 사이즈와 다르거나, 파일 이동이 실패하면 문서 상태를 임시저장으로 돌림
					 */
					if ( ! file_exists($eas_file) OR filesize($eas_file) == 0 )
					{
						$this->ea_document->update_doc_info(array(
							'do_id' => $set_do_id,
							'do_status' => 6,
						));
						redirect('/draft/doc_list_temporary');
					}

					$attach_sum += $fsize_arr[$at_idx]/1024;

					$this->ea_attach->insert(array(
						'do_id' => $set_do_id,
						'at_order' => $at_idx + $pre_uploaded_count,
						'at_origin' => 'html5_uploader',
						'at_real_name' => $realname_arr[$at_idx],
						'at_saved_name' => $fname,
						'at_file_size' => $fsize_arr[$at_idx],
						'at_file_type' => $ftype_arr[$at_idx],
					));

					++$do_attach_count;
				}

				$this->quota_calculator->upsert_size(array("attach_sum" => $attach_sum));

				$this->ea_document->update_doc_info(array(
					'do_id' => $set_do_id,
					'do_attach_count' => $do_attach_count,
				));
			}
		} // end of : $do_status != 6

		$this->session->set_flashdata('do_id', $set_do_id);
		$return_url = "/guide/scenario/doc_list?do_id=" . $set_do_id . "&draft_id=" . $us_id;

		redirect($return_url);
	}

	/**
	 * [Scenario Module] 사용자 생성
	 */
	public function sm_create_user()
	{
		$this->load->model('ea_sosok');
		$sosok_list = $this->ea_sosok->get_all_test(array());

		$this->load->model('ea_users');
		// 가 데이터 만들기
		$so_name_list = array('문근영','장근석','한지민','구혜선','하정우','장동건','강혜정','배수지','김민정','최여진','조여정','김래원','문채원','박예진','이민기','이준기','한효주','공효진','배용준','하석진','서지석','원 빈');
		$title_name_list = array('사원', '사원', '사원', '사원', '사원', '주임', '주임', '주임', '주임', '대리', '대리', '대리', '과장', '차장', '본부장', '지사장', '이사', '상무', '전무', '부사장', '사장', '회장');

		$get_user = $this->ea_users->get_users();
		$get_user_cnt = count($get_user);
		foreach($sosok_list as $key => $sosok)
		{
			$sosok_list[$key]['seq'] = $sosok_list[$key]['so_id']-1;

			for($i = 1; $i <= 22; $i++)
			{
				$id = 'test_so_' . $sosok['so_id'];
				$name = "test_so_" . $sosok['so_id'] . "_" . $so_name_list[$i-1];
				$title = $title_name_list[$i-1];
				$id .= "_" . $i;

				$sosok_list[$key]['tmp_user_list'][$i-1] = array(
					'us_id' => $get_user['us_id'],
					'so_id' => $get_user['so_id'],
					'us_account' => $id,
					'us_pw' => "1q2w3e\$R",
					'us_name' => $name,
					'us_title' => $title,
				);

				if ($get_user_cnt) { // disabled 표시
					foreach($get_user as $user_key => $user)
					{
						if ($user['us_account'] === $id)
						{
							$sosok_list[$key]['tmp_user_list'][$i-1]['disabled'] = TRUE;
						}
					}
				}

			}
		}

		$this->tpl->assign(array(
			'sosok_list' => $sosok_list,
		));
	}

	/**
	 * [Scenario Module] 기안부서에 결과공유
	 */
	public function popup_share_results()
	{
		$data = $this->input->request();

		$line_data = array(
			"do_id" => $data["do_id"],
		);

		$this->load->model("ea_approve_history");
		$this->load->library("get_view");

		$us_id = $this->session->userdata('sess_id');
		// For Test : 결과 공유자 덮어 쓰기
		if ($data['test'])
		{
			$us_id = $data['draft_id'];
		}

		// 원본문서 결재이력등재
		$info = $this->get_view->get_auth(array(
			'do_id' => $data['do_id'],
			'us_id' => $us_id,
		));

		$history_res = $this->ea_approve_history->get_approve_sharing_history(array(
			"do_id" => $data['do_id'],
			"us_id" => $info['recent_approve']['us_id'],
		));

		$this->tpl->assign(array(
			'info' 			=> $info,
			'do_id' 		=> $data["do_id"],
			'do_parent' 	=> $data["do_parent"],
			'al_status' 	=> $info["recent_approve"]["al_status"],
			'al_opinion' 	=> $info["recent_approve"]["al_opinion"],
			'return_url' 	=> array(
				'doc_list' => "/approval/doc_list_approval",
				'doc_view' 			=> "/approval/doc_view?do_id=" . $data["do_parent"],
				'doc_view_default' 	=> "/approval/doc_view",
			),
		));

		// For Test : redirect 덮어쓰기
		if ($data['test'])
		{
			$this->tpl->assign(array(
				'test' => TRUE,
				'test_us_id' => $us_id,
				'return_url' 	=> array(
					'doc_list_test' => "/guide/scenario/doc_list?do_id=" . $data['do_parent'] . "&draft_id=" . $info['doc']['us_id'] . "&sess_id=" . $us_id . "&doc_view=on",
				),
			));
		}

		$this->tpl->define(array(
			'index' => "popup/share_results.html",
		));

		$this->tpl->print_('index');
	}
}

/* End of file test.php*/
/* Location: ./app/controllers/guide.php*/
