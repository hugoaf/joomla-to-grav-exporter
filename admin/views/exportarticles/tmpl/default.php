<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>
<h1>Export Results</h1>
<div style="height: 250px; overflow: auto;">
	<?php foreach ($this->files as $file): ?>
	<?php echo $file."<br>"; ?>
	<?php endforeach ?>	
</div>


<a class="btn" style="padding: 1rem 4rem; margin:3rem;" href="index.php?option=com_j2grav"><i class="fa fa-arrow-right"></i> Back </a>