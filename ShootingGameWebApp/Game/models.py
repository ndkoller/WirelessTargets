from datetime import datetime
from django.db import models


class Player(models.Model):
    name = models.CharField(max_length=200)
    wins = models.IntegerField(default=0)
    TotalGames = models.IntegerField(default=0)
    modifiedBy = models.CharField(max_length=50)
    modifiedDate = models.DateTimeField(default=datetime.now())


class GameType(models.Model):
    name = models.CharField(max_length=200)
    duration = models.IntegerField(default='300')
    numTargets = models.IntegerField(default=1)
    modifiedBy = models.CharField(max_length=50)
    modifiedDate = models.DateTimeField(default=datetime.now())


# Need this to start a particular game
# One game can have many Players
class Game(models.Model):
    name = models.CharField(max_length=200, default='Basic Game')
    playerId = models.ForeignKey(Player, on_delete=models.CASCADE)
    gameTypeId = models.ForeignKey(GameType, on_delete=models.CASCADE)
    modifiedBy = models.CharField(max_length=50)
    modifiedDate = models.DateTimeField(default=datetime.now())

    def __str__(self):
        return f"{self.name} is a {self.gameType.name}"


class Target(models.Model):
    TargetLocation = models.CharField(max_length=200)


class Hit(models.Model):
    TargetId = models.ForeignKey(Target, on_delete=models.CASCADE)
    PointValue = models.IntegerField(default=1)


# Working Mechanical Instance of the game that holds the particular hits
# for a given player in a given game.
class GameInstance (models.Model):
    gameId = models.ForeignKey(Game, on_delete=models.CASCADE)
    playerId = models.ForeignKey(Player, on_delete=models.CASCADE)
    hitId = models.ForeignKey(Hit, on_delete=models.CASCADE)


