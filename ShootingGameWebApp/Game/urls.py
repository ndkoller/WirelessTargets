
from django.urls import path

from . import views

urlpatterns = [
    path('<int:pid>', views.GameTypeDetails, name="GameTypeDetail"),
]
