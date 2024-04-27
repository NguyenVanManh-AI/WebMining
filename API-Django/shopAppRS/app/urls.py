from django.urls import path, include
from rest_framework import routers
from .views import *

# Tạo router
router = routers.DefaultRouter()

# Đăng ký ViewSet với router
router.register('userdata', UserDataViewSet)

# URLs cho ứng dụng
urlpatterns = [
    path('', include(router.urls)),
    path('rs/', RecommenderSystem.as_view()),
]