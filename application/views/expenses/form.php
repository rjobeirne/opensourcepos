<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('expenses/save/'.$expenses_info->expense_id, array('id'=>'Expense_category_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_date'), 'date', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm">
					<span class="glyphicon glyphicon-barcode"></span>
					</span>
					<?php echo form_input(array(
							'name'=>'date',
							'id'=>'datetimepicker',
							'class'=>'form-control input-sm datepicker',
							'value'=>$expenses_info->date)
							);?>
				</div>
			</div>
		</div>
	
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_amount'), 'amount', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'amount',
						'id'=>'amount',
						'class'=>'form-control input-sm',
						'value'=>$expenses_info->amount)
						);?>
			</div>
		</div>

	

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_categories_name'), 'category', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_dropdown('expense_category_id', $expense_categorys, 
				$selected_expense_category, array('class'=>'form-control','id'=>'category')); ?>

		</div>
		</div>

		

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$expenses_info->description)
						);?>
			</div>
		</div>
		
		
		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	
    $('#datetimepicker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
    });

	$('#Expense_category_form').validate($.extend({
		submitHandler:function(form)
		{
		$(form).ajaxSubmit({
			success:function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo site_url('expenses'); ?>', response);
			},
			dataType:'json'
		});

		},
		rules:
		{
			category:"required",
			date:
				{
					required:true,
					
				},
			amount:
				{
					required:true,
					number:true
				}
				
			
		},
		messages:
		{
			category:"<?php echo $this->lang->line('expenses_category_required'); ?>",

			date:
				{
					required:"<?php echo $this->lang->line('expenses_date_required'); ?>",
					
				},
			amount:
				{
					required:"<?php echo $this->lang->line('expenses_amount_required'); ?>",
					number:"<?php echo $this->lang->line('expenses_amount_number'); ?>"
				}
			
		}
	}, dialog_support.error));
});



</script>
