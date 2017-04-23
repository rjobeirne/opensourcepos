<?php
require_once("Report.php");
class summary_expenses_categories extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_expense_categories'), 
			 $this->lang->line('reports_expense_amount'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('category_name, DATE(date) as date, sum(amount) As amount,');
		$this->db->from('expenses');
		
		$this->db->join('people', 'people.person_id = expenses.employee_id', 'LEFT');
		$this->db->join('expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id', 'LEFT');
		
		$this->db->where('DATE(date) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']));

		  $this->db->group_by('category_name');
		  $this->db->order_by('category_name');
		return $this->db->get()->result_array();		
	}
	public function DataSum(array $inputs)
	{
		$this->db->select('DATE(date) as date, sum(amount) As totlsumexpense, 
			count(amount) as Totalcount');
		$this->db->from('expenses');
		$this->db->where('DATE(date) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']));
		return $this->db->get()->result_array();		
	}
	public function DataSum1(array $inputs)
	{
		$this->db->select('sum(amount) As amount');
		$this->db->from('expenses');
		$this->db->where('DATE(date) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']));
		return $this->db->get()->result_array();		
	}
	
	
	public function getSummaryData(array $inputs)
	{

		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit');
		
		$this->db->from('sales_items_temp');

		$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		if ($inputs['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($inputs['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }
          


		// var_dump( $this->db->get()->result_array() );
  //       print_r($this->db->get()->result_array() );
		return $this->db->get()->row_array();
	}
}
?>