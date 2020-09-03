from datetime import date

from django.forms import ModelForm, DateInput, TimeInput, TextInput, IntegerField
from django.core.exceptions import ValidationError

from Game.models import Player


class PlayerForm(ModelForm):
    class Meta:
        model = Player
        fields = '_all_'
        widgets = {
            'name': TextInput(attrs={"type": "string"})
        }

    # date is from the example not necessarily belonging to Player model.
    def clean_date(self):
        d = self.cleaned_data.get("date")
        if d < date.today():
            raise ValidationError("Meetings cannot be in the past")
        return d
