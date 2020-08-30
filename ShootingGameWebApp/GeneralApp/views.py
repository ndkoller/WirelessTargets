from django.shortcuts import render
from django.http import HttpResponse
from datetime import datetime
from Game.models import GameType, Player


def welcome(request):
    return render(request, "GeneralApp/Welcome.html",
                  {"gameTypes": GameType.objects.all(), "players": Player.objects.all()})


def date(request):
    return HttpResponse("This Page was accessed on: " + str(datetime.now()))


def about(request):
    return HttpResponse("This game was designed by Nicholas Koller and Garrett Rademacher.")