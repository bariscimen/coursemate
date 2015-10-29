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
                        <td ng-repeat="hour in hours" style="padding: 0px;"><textarea class="cell" readonly ng-style="cell_color(table[day.code+'_'+($index+1)])" ng-model="table[day.code+'_'+($index+1)]"></textarea></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <hr>
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail" class="control-label col-xs-2">Add a Course:</label>
                    <div class="col-xs-8">
                        <form autocomplete="off">
                            <input type="text" autocomplete="off" ng-model="addCourseTxt" uib-typeahead="course.code+' | '+course.name+' | '+course.instructor+' | '+course.days+' '+course.hours for course in courses | filter:$viewValue | limitTo:20" class="form-control" id="inputEmail" placeholder="Enter a course code or course name...">
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-success" ng-click="addToList(addCourseTxt.split(' | ')[0])">Add</button>
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
                        <td><a href="#" ng-click="removeFromList(mycourse)"><i class="glyphicon glyphicon-trash"></i></a> </td>
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
            <div class="list-group">
                <a href="#" class="list-group-item">Dapibus ac facilisis in <span class="badge">14.01.2015</span></a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in <span class="badge">14.01.2015</span></a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in <span class="badge">14.01.2015</span></a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in <span class="badge">14.01.2015</span></a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in <span class="badge">14.01.2015</span></a>
            </div>
        </div>


    </div>


    </div>
@endsection
