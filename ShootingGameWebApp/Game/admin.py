from django.contrib import admin

from .models import Game, Player, GameType, Target, Hit, GameInstance

admin.site.register(Game)
admin.site.register(Player)
admin.site.register(GameType)
admin.site.register(Target)
admin.site.register(Hit)
admin.site.register(GameInstance)