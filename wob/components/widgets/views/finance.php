<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo Yii::t('main', 'Финансы'); ?>
		<div class="pull-right">
			<select name="id_wallet_select">
				<?php foreach ($wallets as $wallet) { ?>
					<option <?php echo ($wallet_select!==false and $wallet->id==$wallet_select->id) ? 'selected="selected"' : ''; ?> value="<?php echo $wallet->id; ?>">
						<?php echo $wallet->currency->code; ?>
					</option>
				<?php } ?>
			</select>
		</div>

		<?php
		$js = "
			$('select[name=id_wallet_select]').change(function(){
				var id_wallet = $(this).val();
				$.ajax({
					type: 'post',
					data: 'id_wallet_select='+id_wallet,
					url: location.href,
					success: function(html){
						$('#finance_widget_content').replaceWith($('#finance_widget_content', html));
					}
				});
			});
		";
		Yii::app()->clientScript->registerScript('wallet_select',$js);
		?>
	</div>
	<div class="panel-body" id="finance_widget_content">
		<?php
		if ($wallet_select!==false) {
			$amount_payoff_new = $wallet_select->getSumPayoff(WobOrders::STATUS_NEW);
			$amount_payoff_finish = $wallet_select->getSumPayoff(WobOrders::STATUS_FINISH);
			$balance = $wallet_select->volume;
			$balance_full = $amount_payoff_new + $amount_payoff_finish + $balance;
		?>
			<ul class="list-group">
				<li class="list-group-item">
					<span class="badge">
						<?php echo ViewPrice::format($balance_full, $wallet_select->currency->code, $wallet_select->currency->round); ?>
					</span>
					<?php echo Yii::t('main', 'Всего получено'); ?>:
				</li>
				<li class="list-group-item">
					<span class="badge">
						<?php echo ViewPrice::format($amount_payoff_new, $wallet_select->currency->code, $wallet_select->currency->round); ?>
					</span>
					<?php echo Yii::t('main', 'Заказанных на вывод'); ?>:
				</li>
				<li class="list-group-item">
					<span class="badge">
						<?php echo ViewPrice::format($amount_payoff_finish, $wallet_select->currency->code, $wallet_select->currency->round); ?>
					</span>
					<?php echo Yii::t('main', 'Переведено на ваш кошелек'); ?>:
				</li>
				<li class="list-group-item">
					<span class="badge">
						<?php echo ViewPrice::format($balance, $wallet_select->currency->code, $wallet_select->currency->round); ?>
					</span>
					<?php echo Yii::t('main', 'Доступно для вывода'); ?>:
				</li>
			</ul>
			<?php echo CHtml::link(Yii::t('main','Вывести {code} на свой счет', array('{code}'=>$wallet_select->currency->code)), array('/wob/wallet/payoffCreate', 'id'=>$wallet_select->id), array('class'=>'btn btn-primary form-control')); ?>
		<?php } ?>
	</div>
</div>