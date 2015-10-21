<div class="table-responsive">
	<div class="pull-right" style="padding:20px 0">
	<a href="<?php echo $this->route_url("form","home")?>" class="btn btn-default"><i class="fa fa-plus"> Add New</i></a>
	</div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th>Title</th>
                                      
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php foreach ($this->model as $val) {
                                		?>
                                		  <tr>
                                        <td><?php echo $val['id']?></td>
                                        <td><a href="embed/<?php echo $val['url']?>" target="_blank"><?php echo $val['title']?></a></td>
                                       
                                        <td align="center">
                                        	<a href="#" class="btn btn-success btn-xs" title="Embed code"><i>Embed</i></a> <a href="<?php echo $this->route_url("form","home",array('id'=>$val['id']))?>" class="btn btn-info btn-xs" title="Edit"><i class="fa fa-pencil"></i></a> <a href="<?php echo $this->route_url("delete","home",array('id'=>$val['id']))?>" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                		<?php
                                	}
                                	?>
                                  
                                   
                                </tbody>
                            </table>
                        </div>