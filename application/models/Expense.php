<?php
class Expense extends CI_Model
{

	
	/*
	Determines if a given Expense_id is an Expense kit
	*/
	public function exists($expense_id)
	{
		$this->db->from('expenses');
		$this->db->where('expense_id', $expense_id);

		return ($this->db->get()->num_rows() == 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('expenses');
		return $this->db->count_all_results();
	}

	/* 
	get all categories 

	*/
	public function get_expense_category($expense_id)
	{
		$this->db->from('expenses');
		$this->db->where('expense_id', $expense_id);

		return $this->Expense_category->get_info($this->db->get()->row()->expense_category_id);
	}

	/* 
	get all employee 

	*/

	public function get_employee($expense_id)
	{
		$this->db->from('expenses');
		$this->db->where('expense_id', $expense_id);

		return $this->Employee->get_info($this->db->get()->row()->employee_id);
	}

	/*
	Gets rows
	*/
/*
	Gets rows
	*/
	public function get_found_rows($search)
	{
		$this->db->from('expenses');
		$this->db->join('people', 'people.person_id = expenses.employee_id', 'LEFT');
		$this->db->join('expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id', 'LEFT');
		$this->db->group_start();
			$this->db->like('first_name', $search);
			$this->db->or_like('last_name', $search);
			$this->db->or_like('amount', $search);
			$this->db->or_like('CONCAT(first_name, " ", last_name)', $search);
		$this->db->group_end();
	

		return $this->db->get()->num_rows();

	}


	
/*
	/*
	Performs a search on emplyees
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'last_name', $order = 'asc')
	{
		$this->db->from('expenses');
		$this->db->join('people', 'people.person_id = expenses.employee_id');
$this->db->join('expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id');
		$this->db->group_start();
			$this->db->like('first_name', $search);
			$this->db->or_like('date', $search);
			$this->db->or_like('last_name', $search);
			$this->db->or_like('expenses.description', $search);
			$this->db->or_like('expenses.amount', $search);
			$this->db->or_like('expense_categories.category_name', $search);
			$this->db->or_like('CONCAT(first_name, " ", last_name)', $search);
		$this->db->group_end();
		
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();	
	}


	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('expenses');

		//Expense #
		if(stripos($search, 'Expense ') !== FALSE)
		{
			$this->db->like('expense_id', str_ireplace('Expense ', '', $search));
			$this->db->order_by('expense_id', 'desc');

			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('value' => 'Expense '. $row->expense_id, 'label' => 'Expense ' . $row->expense_id);
			}
		}
		else
		{
			$this->db->like('description', $search);
			$this->db->order_by('description', 'desc');

			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('value' => 'Expense ' . $row->expense_id, 'label' => $row->description);
			}
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}

	
	/*
	Returns all the expenses
	*/
	public function get_all($rows = 0, $limit_from = 0)
	{
		$this->db->from('expenses');
		$this->db->join('people', 'people.person_id = expenses.employee_id');
		$this->db->join('expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id');
		$this->db->order_by('date', 'desc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		
		return $this->db->get();	
	}
	
	


	/*
	Gets information about a particular item
	*/
	public function get_info($expense_id)
	{
		
		//$this->db->select('expense_categories.category_name');
		$this->db->from('expenses');		
		$this->db->join('expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id');
		$this->db->where('expense_id', $expense_id);

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row();

		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$expenses_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('expenses') as $field)
			{
				$expenses_obj->$field = '';
			}

			return $expenses_obj;
		}
	}



	/*
	Inserts or updates an expense_category
	*/
	public function save(&$expense_data, $expense_id = FALSE)
	{
		if(!$expense_id || !$this->exists($expense_id))
		{
			if($this->db->insert('expenses', $expense_data))
			{
				$expense_data['expense_id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('expense_id', $expense_id);

		return $this->db->update('expenses', $expense_data);
	}


	/*
	Deletes a list of expense_category
	*/
	public function delete_list($expense_ids)
	{
		$this->db->where_in('expense_id', $expense_ids);

		return $this->db->delete('expenses');		
 	}
	
	/*
	 Get the payment summary for the takings (sales/manage) view
	*/
	public function get_payments_summary($search, $filters)
	{
		// get payment summary
		$this->db->select('count(*) AS count, SUM(amount) AS amount');
		$this->db->from('expenses AS expenses');
		

		if (empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE(expenses.date) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('expenses.date BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
		}
		$this->db->group_by('amount');

		
		$payments = $this->db->get()->result_array();
		return $payments;
	}



}
?>