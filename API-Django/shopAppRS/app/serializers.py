from rest_framework import serializers
from .models import UserData

class UserDataSerializer(serializers.ModelSerializer):
    class Meta:
        model = UserData
        fields = ('id', 'id_user', 'recent_care', 'recent_add', 'recent_buy')