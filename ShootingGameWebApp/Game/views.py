from django.shortcuts import render, get_object_or_404
from datetime import datetime, timedelta
from .models import GameType


def detail(request, id):
    gameType = get_object_or_404(GameType, pk=id)
    return render(request, "Game/detail.html", {"gameType": gameType, "gameTime": duration(gameType)})


def duration(game):
    return timedelta(seconds=game.duration)
