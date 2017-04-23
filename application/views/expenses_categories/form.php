<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('expenses_categories/save/'.$category_info->expense_category_id, array('id'=>'Expense_category_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_kit_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_categories_name'), 'category_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'category_name',
						'id'=>'category_name',
						'class'=>'form-control input-sm',
						'value'=>$category_info->category_name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_categories_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$category_info->description)
						);?>
			</div>
		</div>

		

		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	

	$('#Expense_category_form').validate($.extend({
		submitHandler:function(form)
		{
		$(form).ajaxSubmit({
			success:function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo site_url('expenses_categories'); ?>', response);
			},
			dataType:'json'
		});

		},
		rules:
		{
			category_name:"required",
			
		},
		messages:
		{
			category_name:"<?php echo $this->lang->line('category_name_required'); ?>",
			
		}
	}, dialog_support.error));
});



</script>