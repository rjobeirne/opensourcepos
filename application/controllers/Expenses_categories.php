<?php
require_once ("Secure_area.php");
require_once ("interfaces/Idata_controller.php");

class Expenses_categories extends Secure_area
{
	function __construct()
	{
		parent::__construct('expenses_categories');
	}
	
	// add the total cost and retail price to a passed items kit retrieving the data from each singolar item part of the kit
	
	function index()
	{
		 $data['controller_name'] = $this->get_controller_name();
		 $data['table_headers'] = get_expense_category_manage_table_headers();
		 $this->load->view('expenses_categories/manage', $data);
		
	}

	/*
	Returns expense_category_manage table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$expense_categorys = $this->Expense_category->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Expense_category->get_found_rows($search);

		$data_rows = array();
		foreach($expense_categorys->result() as $expense_category)
		{
			$data_rows[] = get_expense_category_data_row($expense_category, $this);
		}
		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	function suggest_search()
	{
		$suggestions = $this->Expense_category->get_search_suggestions($this->input->post('term'));
		echo json_encode($suggestions);
	}




	function view($expense_category_id=-1)
	{
		$data['category_info'] = $this->Expense_category->get_info($expense_category_id);
		$this->load->view("expenses_categories/form", $data);
	}


	
	function save($expense_category_id=-1)
	{
		$expense_category_data = array(
			'category_name' => $this->input->post('category_name'),
			'description' => $this->input->post('description')
		);
		
		if ($this->Expense_category->save($expense_category_data, $expense_category_id))
		{
			$success = TRUE;
			//New expense_category_id
			if ($expense_category_id==-1) {
				$expense_category_id = $expense_category_data['expense_category_id'];
			}

			
			echo json_encode(array('success'=>$success,
								'message'=>$this->lang->line('expenses_categories_successful_adding').' '.$expense_category_data['category_name'],
								'id'=>$expense_category_id));
		}
		else//failure
		{
			echo json_encode(array('success'=>false, 
								'message'=>$this->lang->line('expenses_categories_error_adding_updating').' '.$expense_category_data['category_name'],
								'id'=>-1));
		}
	}
	
	function delete()
	{
		$expense_category_to_delete = $this->input->post('ids');

		if ($this->Expense_category->delete_list($expense_category_to_delete))
		{
			echo json_encode(array('success'=>true,
								'message'=>$this->lang->line('expenses_categories_successful_deleted').' '.count($expense_category_to_delete).' '.$this->lang->line('expenses_categories_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,
								'message'=>$this->lang->line('expenses_categories_cannot_be_deleted')));
		}
	}
	
	
}
?>