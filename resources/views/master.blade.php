<!DOCTYPE html>
<html lang="en" ng-app="courseMate">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    {{--<link rel="icon" href="{{ url('/') }}favicon.ico">--}}

    <title>Boun CourseMate!</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
    <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="app" ng-controller="appController">

@include('navbar')
<div class="container">


    @yield('content')

</div>
<!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        $( document ).on( 'focus', ':input', function(){
            $( this ).attr( 'autocomplete', 'off' );
        });
    });
</script>
<script>
    //var courseMate = angular.module('courseMate', ['ngAnimate', 'ui.bootstrap']);
    var courseMate = angular.module('courseMate', ['ui.bootstrap']);
    courseMate.controller('appController', function ($scope, $http) {

        $scope.cell_color = function (value) {
            if (value) {
                if (value.indexOf("\n") == -1) {
                    return {"background-color": "#C4FFC0"}
                } else {
                    return {"background-color": "#FFC0C0"}
                }
            } else {
                return {"background-color": "#FFFFC0"}
            }
        };

        $scope.days = [
            {
                'code': 'M',
                'name': 'Monday'
            },
            {
                'code': 'T',
                'name': 'Tuesday'
            },
            {
                'code': 'W',
                'name': 'Wednesday'
            },
            {
                'code': 'Th',
                'name': 'Thursday'
            },
            {
                'code': 'F',
                'name': 'Friday'
            },
            {
                'code': 'St',
                'name': 'Saturday'
            },
            {
                'code': 'S',
                'name': 'Sunday'
            }
        ];

        $scope.hours = [
            {'code': '1', 'slot': '09:00-09:50'},
            {'code': '2', 'slot': '10:00-10:50'},
            {'code': '3', 'slot': '11:00-11:50'},
            {'code': '4', 'slot': '12:00-12:50'},
            {'code': '5', 'slot': '13:00-13:50'},
            {'code': '6', 'slot': '14:00-14:50'},
            {'code': '7', 'slot': '15:00-15:50'},
            {'code': '8', 'slot': '16:00-16:50'},
            {'code': '9', 'slot': '17:00-17:50'},
            {'code': '10', 'slot': '18:00-18:50'},
            {'code': '11', 'slot': '19:00-19:50'},
            {'code': '12', 'slot': '20:00-20:50'},
            {'code': '13', 'slot': '21:00-21:50'}];

        $scope.table = {};

        $scope.list = [];

        /*$scope.$watch('list', function(newValue, oldValue){
         angular.forEach($scope.list, function(value, key){
         var days = $course.days.split('');
         var hours = $course.hours.split('');
         console.log(key + ': ' + value);
         });
         });*/

        $scope.addToList = function ($course_code) {

            angular.forEach($scope.courses, function (course, key) {
                if(course.code == $course_code){
                    $course = course;
                    //return course;
                }
            });

            var add = true;
            angular.forEach($scope.list, function (list_course, key) {
                if(list_course.code == $course.code){
                    add = false;
                }
            });
            if(add){
                $scope.list.push($course);
                $scope.updateTable();
                $scope.addCourseTxt ="";
            }

        };

        $scope.removeFromList = function ($course) {

            angular.forEach($scope.list, function (list_course, key) {
                if(list_course.code == $course.code){
                    $scope.list.splice(key, 1);
                }
            });

            $scope.updateTable();
        };

        Array.prototype.clean=function(){return this.filter(function(e){return (typeof  e !=='undefined')&&(e!= null)&&(e!='')})}

        $scope.updateTable = function () {
            $scope.table = {};

            angular.forEach($scope.list, function (course, k) {

                //var c_days = course.days.split('');
                var c_days = [];
                var c_hours = course.hours.split('');
                var c_rooms = course.rooms.split('|').clean();
                angular.forEach(course.days.split(''), function (c_day, k) {
                    if(c_day == 't'){
                        c_days[c_days.length - 1] = 'St';
                    }else if(c_day == 'h'){
                        c_days[c_days.length - 1] = 'Th';
                    }else{
                        c_days.push(c_day);
                    }
                });

                angular.forEach($scope.days, function (day, k) {
                    angular.forEach($scope.hours, function (hour, k2) {

                        var name = day.code + '_' + hour.code;

                        if(c_days.indexOf(day.code) != -1){
                            if(c_hours[c_days.indexOf(day.code)] == hour.code){
                                c_days[c_days.indexOf(day.code)] = 'X';

                                //console.log(typeof c_rooms[c_hours.indexOf(hour.code)]);
                                if($scope.table[name]){
                                    if(c_rooms.length > 0 && c_rooms[c_hours.indexOf(hour.code)].trim().length > 0){
                                        $scope.table[name] += "\n"+course.code+'['+c_rooms[c_hours.indexOf(hour.code)].trim()+']';
                                    }else{
                                        $scope.table[name] += "\n"+course.code;
                                    }
                                }else{
                                    if(c_rooms.length > 0 && c_rooms[c_hours.indexOf(hour.code)].trim().length > 0){
                                        $scope.table[name] = course.code+'['+c_rooms[c_hours.indexOf(hour.code)].trim()+']';
                                    }else{
                                        $scope.table[name] = course.code;
                                    }
                                }
                            }
                        }
                    });
                });

            });
            //$scope.$apply();
        };


        $http.get('courses.json').success(function (data) {
            $scope.courses = data;
        });
        $http.get('news.json').success(function (data) {
            $scope.news = data;
        });
    });
</script>
</body>
</html>
