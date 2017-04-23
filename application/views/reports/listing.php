<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:18px;margin-top:8px;"><?php echo $this->lang->line('reports_reports'); ?></div>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}
?>
<ul id="report_list">
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-stats">&nbsp</span><?php echo $this->lang->line('reports_graphical_reports'); ?></h3>
		  	</div>

			<div class="list-group">
			<li id="PopularPosts1">	
			<ul>
				<?php
				foreach($grants as $grant) 
				{
					if (!preg_match('/reports_(inventory|receivings)/', $grant['permission_id']))
					{
						show_report('graphical_summary', $grant['permission_id']);
					}
				}
				?></ul></li>
			 </div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list">&nbsp</span><?php echo $this->lang->line('reports_summary_reports'); ?></h3>
		  	</div>
			<div class="list-group">
			<li id="PopularPosts1">	
			<ul>
				<?php 
				foreach($grants as $grant) 
				{
					if (!preg_match('/reports_(inventory|receivings)/', $grant['permission_id']))
					{
						show_report('summary', $grant['permission_id']);
					}
				}
				?></ul>
				</li>
			 </div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt">&nbsp</span><?php echo $this->lang->line('reports_detailed_reports'); ?></h3>
		  	</div>
			<div class="list-group">
			<li id="PopularPosts1">	
			<ul>
				<?php 			
				$person_id = $this->session->userdata('person_id');
				show_report_if_allowed('detailed', 'sales', $person_id);
				show_report_if_allowed('detailed', 'receivings', $person_id);
				show_report_if_allowed('specific', 'customer', $person_id, 'reports_customers');
				show_report_if_allowed('specific', 'waiter', $person_id, 'reports_waiters');
				show_report_if_allowed('specific', 'discount', $person_id, 'reports_discounts');
				show_report_if_allowed('specific', 'employee', $person_id, 'reports_employees');
				show_report_if_allowed('specific', 'cashup', $person_id, 'reports_cashups');
				?></ul>
				</li>
			 </div>
		</div>

		<?php
		if ($this->Employee->has_grant('reports_inventory', $this->session->userdata('person_id')))
		{
		?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-book">&nbsp</span><?php echo $this->lang->line('reports_inventory_reports'); ?></h3>
				</div>
				<div class="list-group">
				<li id="PopularPosts1">	
			<ul>
				<?php 
					show_report('', 'reports_inventory_low');
					show_report('', 'reports_inventory_summary');
				?></ul></li>
				</div>
			</div>
		<?php 
		}
		?></ul></li>
		
		</ul>
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>