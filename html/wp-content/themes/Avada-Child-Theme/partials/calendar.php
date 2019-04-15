<div class = 'container calendar-container relative mt-5 {{ $ctrl.loading  ? "loading" : ""}}' ng-show="$ctrl.currentView == 'calendar'" >
	<div class = 'view-switcher  pb-3'>
		<a class = "{{$ctrl.currentCalView == 'month' ? 'active' : ''}}" ng-click="$ctrl.toggleCalendarView( 'month' )">Month</a>
		<a class = "{{$ctrl.currentCalView == 'list' ? 'active' : ''}}" ng-click="$ctrl.toggleCalendarView( 'list' )">List</a>
	</div>
	<div class = 'loader'><span class = "fa fa-spin fa-refresh"></span></div>
	<div id='calendar'></div>
	<div class = 'text-center mt-3'>
		<h4 class = 'mg-h4'>Week Starts on Sunday</h4>
	</div>
</div>
