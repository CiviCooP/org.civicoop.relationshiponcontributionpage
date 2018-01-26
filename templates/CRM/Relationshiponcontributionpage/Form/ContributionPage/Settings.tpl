{capture assign='soft_credit_relationship_enabled_block'}{strip}
<tr id="soft_credit_relationship_enabled_block">
  <td class="label"></td>
  <td class="">{$form.soft_credit_relationship_enabled.html}&nbsp;{$form.soft_credit_relationship_enabled.label}</td>
</tr>
<tr class="soft_credit_relationship_enabled_subblock">
  <td class="label">{$form.soft_credit_relationship_label.label}</td>
  <td class="">{$form.soft_credit_relationship_label.html}</td>
</tr>
<tr class="soft_credit_relationship_enabled_subblock">
  <td class="label">{$form.soft_credit_relationship_options.label}</td>
  <td class="">{$form.soft_credit_relationship_options.html}</td>
</tr>
{/strip}{/capture}
<script type="text/javascript">
{literal}
cj('#honor').append('{/literal}{$soft_credit_relationship_enabled_block|escape:'javascript'}{literal}');
cj('#soft_credit_relationship_enabled').change(function() {
	if (this.checked) {
		cj('.soft_credit_relationship_enabled_subblock').removeClass('hiddenElement');
	} else {
		cj('.soft_credit_relationship_enabled_subblock').addClass('hiddenElement');
	}
});
cj('#soft_credit_relationship_enabled').trigger('change');
{/literal}
</script>