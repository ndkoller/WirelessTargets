from django.shortcuts import render
from django.http import HttpResponse
from datetime import datetime


def welcome(request):
    return HttpResponse("Welcome to the Shooting Game.")


def date(request):
    return HttpResponse("This Page was accessed on: " + str(datetime.now()))


def about(request):
    return HttpResponse("This game was designed by Nicholas Koller and Garrett Rademacher.")