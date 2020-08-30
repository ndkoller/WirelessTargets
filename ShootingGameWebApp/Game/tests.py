from django.test import TestCase

# Create your tests here.
from datetime import datetime, timedelta
from .models import GameType
from django.shortcuts import render, get_object_or_404

#
# def duration(game):
#     return timedelta(seconds=game.duration)
#
# gameType = get_object_or_404(GameType, pk=1)
# print(duration(gameType))