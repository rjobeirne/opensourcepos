<?php
require_once ("Secure_area.php");
require_once ("interfaces/Idata_controller.php");

class Expenses extends Secure_area
{
	function __construct()
	{
		parent::__construct('expenses');
	}


	function index()
	{
		 $data['controller_name'] = $this->get_controller_name();
		 $data['table_headers'] = get_expenses_manage_table_headers();
		 $data['filters'] = array('by_category' => $this->lang->line('expenses_by_category'));
		 $this->load->view('expenses/manage', $data);
		
	}

	function search()
	{
		$sum_amount_expense = 0;
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$filters = array(
						 'start_date' => $this->input->get('start_date'),
						'end_date' => $this->input->get('end_date')
						 );

		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);
		$expenses = $this->Expense->search($search, $limit, $filters, $offset, $sort, $order);
		$total_rows = $this->Expense->get_found_rows($search, $filters);
		$payments = $this->Expense->get_payments_summary($search, $filters);
		//$payment_summary = get_expenses_manage_payments_summary($payments, $expenses, $this);
	
		$data_rows = array();
		foreach($expenses->result() as $expense)
		{
			$data_rows[] = get_expenses_data_row($expense, $this);
			
		}

		
		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));

	}

	function view($expense_id=-1)
	{
		$data['expenses_info'] = $this->Expense->get_info($expense_id);
		$expense_categorys = array('' => $this->lang->line('items_none'));
		foreach($this->Expense_category->get_all()->result_array() as $row)
		 {
			$expense_categorys[$row['expense_category_id']] = $row['category_name'];
		}		
		$data['expense_categorys']=$expense_categorys; 
		$data['selected_expense_category']=$data['expenses_info']->expense_category_id;
		$this->load->view("expenses/form", $data);
	}

	
	function save($expense_id=-1)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		// $date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $this->input->post('date'));
		$expense_data = array(
			//'date' => $date_formatter->format('Y-m-d H:i:s'),
			'date'=>$this->input->post('date'),
			'amount'=>$this->input->post('amount'),
			'expense_category_id'=>$this->input->post('expense_category_id'),
			'description'=>$this->input->post('description'),
			'employee_id'=>$employee_id
		);
		if ($this->Expense->save($expense_data, $expense_id))
		{
			$success = TRUE;
			//New expense_id
			if ($expense_id==-1) {
				$expense_id = $expense_data['expense_id'];
			}
			echo json_encode(array('success'=>$success,
								'message'=>$this->lang->line('expenses_successful_adding').' '.$expense_data['description'],
								'id'=>$expense_id));
		}
		else//failure
		{
			echo json_encode(array('success'=>false, 
								'message'=>$this->lang->line('expenses_error_adding_updating').' '.$expense_data['description'],
								'id'=>-1));
		}
	}
	
	function delete()
	{
		$expense_to_delete = $this->input->post('ids');

		if ($this->Expense->delete_list($expense_to_delete))
		{
			echo json_encode(array('success'=>true,
								'message'=>$this->lang->line('expenses_successful_deleted').' '.count($expense_to_delete).' '.$this->lang->line('expenses_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,
								'message'=>$this->lang->line('expenses_cannot_be_deleted')));
		}
	}

	
	


}
?>