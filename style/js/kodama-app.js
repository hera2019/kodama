var app = angular.module('adminbsb', ['ngRoute']);

app.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'page/welcome.php'
        })
        .when('/useredit', {
            templateUrl: 'user/useredit.php'
        })
        .when('/download', {
            templateUrl: 'templates/download.html'
        })
        .when('/javascript-options', {
            templateUrl: 'templates/javascript-options.html'
        })
        .when('/component-card', {
            templateUrl: 'templates/component-card.html'
        })
        .when('/component-infobox', {
            templateUrl: 'templates/component-infobox.html'
        })
        .when('/welcome', {
            templateUrl: 'page/welcome.php'
        })
        .when('/content', {
            templateUrl: 'page/content.php'
        })
        .when('/pagetest', {
            templateUrl: 'page/pagetest.php'
        })
        .when('/htmlpagetest', {
            templateUrl: 'page/htmlpagetest.html'
        })
        .otherwise({
            templateUrl: 'page/welcome.php'
        });
});

app.run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, next, current) {
        $rootScope.url = $location.$$path.replace('/', '');
    });
});

function routeChanged(scope, callback) {
    scope.$on('$routeChangeSuccess', callback());
}
