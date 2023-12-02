        <div class="modal fade" id="viewFaculty<?php echo $row['id'];?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label>Employee ID:</label>
                                <input type="text" class="form-control" value="<?php echo $row['emp_id'];?>" readonly>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Faculty Name:</label>
                                <input type="text" class="form-control" value="<?php echo $row['name'];?>" readonly>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Department:</label>
                                <input type="text" class="form-control" value="<?php echo $row['dept'];?>" readonly>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Course Handled:</label>
                                <input type="text" class="form-control" value="<?php echo $row['course_name'];?>" readonly>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Email:</label>
                                <input type="text" class="form-control" value="<?php echo $row['email'];?>" readonly>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>