@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <legend>Weekly Schedule</legend>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <thead>
                    <tr class="active">
                        <th>Days/Hours</th>
                        <th ng-repeat="hour in hours">@{{hour.slot}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="day in days">
                        <th>@{{ day.name }}</th>
                        <td ng-repeat="hour in hours" style="padding: 0px;"><textarea class="cell" readonly
                                                                                      ng-style="cell_color(table[day.code+'_'+($index+1)])"
                                                                                      ng-model="table[day.code+'_'+($index+1)]"></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <hr>
            <form autocomplete="off" class="form-horizontal">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group text-center">
                            <label for="inputCourse" class="col-md-2 col-xs-12 control-label" style="text-align: center;">Add a Course</label>

                            <div class="col-md-8 col-xs-10">
                                <input type="text" autocomplete="off" ng-model="addCourseTxt"
                                       uib-typeahead="course.code+' | '+course.name+' | '+course.instructor+' | '+course.days+' '+course.hours for course in courses | filter:$viewValue | limitTo:20"
                                       class="form-control" id="inputCourse"
                                       placeholder="Enter a course code or course name...">
                            </div>
                            <button class="btn btn-success" ng-click="addToList(addCourseTxt.split(' | ')[0])">Add
                            </button>
                        </div>
                    </div>


                </div>

            </form>
            <hr>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <legend>My Course List</legend>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <thead>
                    <tr class="active">
                        <th></th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Instr.</th>
                        <th>Cr./Ects</th>
                        <th>Days</th>
                        <th>Hours</th>
                        <th>Rooms</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="mycourse in list">
                        <td><a href="#" ng-click="removeFromList(mycourse)"><i
                                        class="glyphicon glyphicon-trash"></i></a></td>
                        <td>@{{ mycourse.code }}</td>
                        <td>@{{ mycourse.name }}</td>
                        <td>@{{ mycourse.instructor }}</td>
                        <td>@{{ mycourse.credits }}</td>
                        <td>@{{ mycourse.days }}</td>
                        <td>@{{ mycourse.hours }}</td>
                        <td>@{{ mycourse.rooms }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-md-4">
            <legend>Announcements & News</legend>
            <div class="list-group" ng-repeat="item in news">
                <a href="#" class="list-group-item" data-toggle="modal" data-target="#news@{{ $index }}">@{{ item.title }} <span class="badge">@{{ item.date }}</span></a>

                <div class="modal fade" id="news@{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">@{{ item.title }}</h4>
                            </div>
                            <div class="modal-body">
                                @{{ item.content }}
                            </div>
                            <div class="modal-footer">
                                <span class="badge" style="float:left;">@{{ item.date }}</span>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>


    </div>
@endsection
