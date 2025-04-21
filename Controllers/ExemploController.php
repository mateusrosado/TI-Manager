<?php

class ExemploController extends Controller
{

	private $data = array();

	public function __construct()
	{
	}

	public function index()
	{

		$this->data['nivel-1'] = 'Exemplo';

		$exemplo = new ExemploModel();

		$idParaUsarnoMetodo = 1;
		$this->data['exemplos'] = $exemplo->exemploDeUso($idParaUsarnoMetodo);

		$this->data['JS'] .= customJS('/libs/sweetalert/sweetalert2.all.min');
		$this->data['JS'] .= customJS('/libs/sweetalert/warnings');

		if (isset ($_SESSION['msg'])) {
			$this->data['JS'] .= "
				<script>
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						showCloseButton: true,
						timer: 6000,
					timerProgressBar: true,
					});
					Toast.fire({
						icon: '" . $_SESSION['msg']['type'] . "',
						title: '" . $_SESSION['msg']['message'] . "'
					});
				</script>
			";

			unset($_SESSION['msg']);
		}

		$this->loadTemplateAdmin('Admin/Exemplo/index', $this->data);
	}


}
