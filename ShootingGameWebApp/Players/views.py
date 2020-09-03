from django.shortcuts import render, get_object_or_404, redirect
from django.forms import modelform_factory
from Game.models import Player


def PlayerInfoDetails(request, pid):
    player = get_object_or_404(Player, pk=pid)
    return render(request, "Players/PlayerInfo.html", {"Player": player})


PlayerForm = modelform_factory(Player, exclude=[Player.modifiedBy, Player.modifiedDate])


def new(request):
    if request.method == "POST":
        form = PlayerForm(request.POST)
        if form.is_valid():
            form.save()
            return redirect("home")
    else:
        form = PlayerForm()
    return render(request, "Players/NewPlayer.html", {"form": form})
