<form role="form" action="<?php echo  $this->route_url('save', 'home');?>" method="POST">

                           <input type="hidden" name="id" value="<?php echo $model['id']?>" />

                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" name="title" value="<?php echo $model['title']?>" placeholder="Enter title" required>
                            </div>
                            <div class="form-group">
                                <label>Video links</label>
                               
                            </div>
                            <div class="input_fields_wrap">
                                <?php if($model['links']) { 
                                    foreach ($model['links'] as $val) {
                                        ?>
                                        <div class="form-group input-group">
                                 <input class="form-control" type="url" placeholder="Enter video link" value="<?php echo $val['link'] ?>" name="link[]" required>
                              <span class="input-group-btn"><a href="#" class="remove_field btn btn-danger"><i class="fa fa-times"></i></a></span>
                                 </div>
                           

                                        <?php
                                    }
                                }
                                else { 
                                    ?>
                                     <div class="form-group">
                                 <input class="form-control" type="url" placeholder="Enter video link" name="link[]" required>
                              
                                 </div>
                                    <?php
                                }
                                    ?>
                              
                            </div>
                            <div class="form-group">
                               <button class="add_field_button btn btn-primary">Add More Fields</button>
                            </div>

                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="<?php echo $this->route_url('index', 'home');?>" class="btn btn-default">Cancel</a> 
                            </div>
                        </form>