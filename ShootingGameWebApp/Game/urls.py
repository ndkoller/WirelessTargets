
from django.urls import path

from . import views

urlpatterns = [
    path('<int:pid>', views.GameTypeDetails, name="GameTypeDetail"),
    path('Launch/<int:pid>', views.GameLaunch, name="GameLaunch"),
]
