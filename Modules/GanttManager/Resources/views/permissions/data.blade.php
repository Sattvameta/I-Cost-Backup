                  @php $privious_value = ''; $i=0;
                  foreach($permissions as $permission){
                  $newPermission[$permission->module][$i]=$permission;
                  $i++;
                  }
                  @endphp
                  <div class="row">

                    <div class="col-sm-12">

                      <div class="permissions-listing">
                        <table class="table table-hover text-nowrap">
                          <thead>
                            <tr>
                              <th>Section</th>
                              <th> Read</th>
                              <th> Write</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($newPermission as $key=> $newpermission)
                            <tr>
                              <td class="col-md-6 permissions-col">{{$key }}</td>
                              @foreach($newpermission as $permission)

                              <td>
                                <div class="col-md-9 permissions-col chk">
                                  <div class="icheck-success d-inline">


                                    <input type="checkbox" id="permission{{$permission->id}}" name="permission_id[]" value="{{$permission->id}}" {{(!in_array($permission->id, $permissionsIds))?'':'checked'}}>
                                    <!--<label for="permission{{$permission->id}}">    {{ ucwords(str_replace('-', ' ', $permission->name)) }} ({{ $permission->slug }})  </label> -->
                                    <label for="permission{{$permission->id}}"> {{ ucwords($permission->label)}} </label> </div>
                                  @php $privious_value = $permission->module; @endphp

                                </div>
                              </td>

                              @endforeach
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>

                    @if(Auth::user()->roles->first()->slug =='super_admin')
                      <div class="col-md-12">
                        <div class="form-group">
                          {{Form::label('default_permission', 'Set Permission')}}
                          {!!Form::select('default_permission', ['0'=>'Company Permission','1'=>'Default Permission'], $is_default, ['class' => 'form-control'])!!}

                        </div>
                      </div>
                    @endif
                  </div>