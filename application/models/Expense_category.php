<?php
class Expense_category extends CI_Model
{

	/*
	Determines if a given Expense_id is an Expense kit
	*/
	public function exists($expense_category_id)
	{
		$this->db->from('expense_categories');
		$this->db->where('expense_category_id', $expense_category_id);

		return ($this->db->get()->num_rows() == 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('expense_categories');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}
	

	/*
	Gets information about a particular category
	*/
	public function get_info($expense_category_id)
	{
		$this->db->from('expense_categories');
		$this->db->where('expense_category_id', $expense_category_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$expense_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('expense_categories') as $field)
			{
				$expense_obj->$field = '';
			}

			return $expense_obj;
		}
	}
	/*
	Returns all the expense_categories
	*/
	public function get_all($rows = 0, $limit_from = 0)
	{
		$this->db->from('expense_categories');
		$this->db->order_by('category_name', 'asc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();		
	}

	/*
	Gets information about multiple expense_category_id
	*/
	public function get_multiple_info($expense_category_ids)
	{
		$this->db->from('expense_categories');
		$this->db->where_in('expense_category_id', $expense_category_ids);
		$this->db->order_by('category_name', 'asc');

		return $this->db->get();
	}


	/*
	Inserts or updates an expense_category
	*/
	public function save(&$expense_category_data, $expense_category_id = FALSE)
	{
		if(!$expense_category_id || !$this->exists($expense_category_id))
		{
			if($this->db->insert('expense_categories', $expense_category_data))
			{
				$expense_category_data['expense_category_id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('expense_category_id', $expense_category_id);

		return $this->db->update('expense_categories', $expense_category_data);
	}


	/*
	Deletes one expense_category
	*/
	public function delete($expense_category_id)
	{
		return $this->db->delete('expense_category', 
			array('expense_category_id' => $id));

	}

	/*
	Deletes a list of expense_category
	*/
	public function delete_list($expense_category_ids)
	{
		$this->db->where_in('expense_category_id', $expense_category_ids);

		return $this->db->delete('expense_categories');		
 	}


 	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('expense_categories');

		//Expense #
		if(stripos($search, 'Expense ') !== FALSE)
		{
			$this->db->like('expense_category_id', str_ireplace('Expense ', '', $search));
			$this->db->order_by('expense_category_id', 'asc');

			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('value' => 'Expense '. $row->expense_category_id, 'label' => 'Expense ' . $row->expense_category_id);
			}
		}
		else
		{
			$this->db->like('category_name', $search);
			$this->db->order_by('category_name', 'asc');

			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('value' => 'Expense ' . $row->expense_category_id, 'label' => $row->category_name);
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
	Perform a search on expense_category
	*/
	public function search($search, $rows=0, $limit_from=0, $sort='category_name', $order='asc')
	{
		$this->db->from('expense_categories');
		$this->db->like('category_name', $search);
		$this->db->or_like('description', $search);

		//KIT #
		if(stripos($search, 'Expense ') !== FALSE)
		{
			$this->db->or_like('expense_category_id', str_ireplace('Expense ', '', $search));
		}

		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();	
	}
	
	public function get_found_rows($search)
	{
		$this->db->from('expense_categories');
		$this->db->group_start();
			$this->db->like('category_name', $search);
			$this->db->or_like('description', $search);
		$this->db->group_end();

		return $this->db->get()->num_rows();
	}


}
?>