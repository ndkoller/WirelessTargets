from django.shortcuts import render, get_object_or_404
from datetime import datetime, timedelta
from .models import GameType


def GameTypeDetails(request, pid):
    gameType = get_object_or_404(GameType, pk=pid)
    return render(request, "Game/detail.html", {"gameType": gameType, "gameTime": duration(gameType)})


def GameLaunch(request, pid):
    gameType = get_object_or_404(GameType, pk=pid)
    return render(request, "Launch/GameLaunch.html", {"gameType": gameType, "gameTime": duration(gameType)})


def duration(game):
    return timedelta(seconds=game.duration)
