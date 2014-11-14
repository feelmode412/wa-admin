<tr>
	<td>Name *</td>
	<td>:</td>
	<td>{{ Form::text('name', @$row->name, array('class' => 'required')) }}</td>
</tr>

@if ( ! Input::get('id') || Input::get('id') > 1)
	<tr>
		<td style="vertical-align: top">Menu Access</td>
		<td style="vertical-align: top">:</td>
		<td>
			<?php $currentRoutes = \Webarq\Admin\User\Role\Route::whereAdminRoleId(Input::get('id'))->lists('route', 'id') ?>

			<ul id="checkboxtree">
				<?php foreach (Config::get('admin::menu') as $level1): ?>

					<li><?php echo Form::checkbox('routes[]', $level1['route'], in_array($level1['route'], $currentRoutes)) ?>
					
					<label><?php echo $level1['title'] ?>
					
					<?php if ( ! isset($level1['subs'])): continue; endif ?>
					
					<?php foreach ($level1['subs'] as $level2): ?>
						<ul>
							<li><?php echo Form::checkbox('routes[]', $level2['route'], in_array($level2['route'], $currentRoutes)) ?>
							<label><?php echo $level2['title'] ?>
							
							<?php if ( ! isset($level2['subs'])): ?>
								</ul>
								<?php continue ?>
							<?php endif ?>
							
							<?php foreach ($level2['subs'] as $level3): ?>
								<ul>
									<li><?php echo Form::checkbox('routes[]', $level3['route'], in_array($level3['route'], $currentRoutes)) ?>
									<label><?php echo $level3['title'] ?>
								</ul>
							<?php endforeach ?>

						</ul>
					<?php endforeach ?>

				<?php endforeach ?>
			</ul>
			
		</td>
	</tr>
@endif

