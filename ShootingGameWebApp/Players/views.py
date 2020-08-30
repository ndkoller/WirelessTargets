from django.shortcuts import render, get_object_or_404
from Game.models import Player


def PlayerInfoDetails(request, pid):
    player = get_object_or_404(Player, pk=pid)
    return render(request, "Players/PlayerInfo.html", {"Player": player})
