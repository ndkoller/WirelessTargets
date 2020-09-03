
from django.urls import path

from . import views

urlpatterns = [
    path('<int:pid>', views.PlayerInfoDetails, name="PlayerDetail"),
    path('NewPlayer', views.new, name="new")
]
