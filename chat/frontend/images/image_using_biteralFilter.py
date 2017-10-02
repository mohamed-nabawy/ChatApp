import cv2
import numpy as np
from matplotlib import pyplot as plt

img = cv2.imread('login1.jpg')

result = cv2.bilateralFilter(img,7,175,175)
# result = cv2.medianBlur(result,9)

cv2.imwrite('mylogin.jpg',result)