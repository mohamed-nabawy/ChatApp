import cv2
import numpy as np
from matplotlib import pyplot as plt

img = cv2.imread('login4.jpg')

# # step 1 : scale original image
scaledImage = cv2.resize(img,None,fx=1.62, fy=1.62, interpolation = cv2.INTER_CUBIC)

# step 2 : apply 2D filter to sharpen the image
kernel = np.array([[-1,-1,-1], [-1,9,-1], [-1,-1,-1]])
result = cv2.filter2D(scaledImage, -1, kernel)

# step 3 : remove any noise with any filter
result = cv2.bilateralFilter(result,20,175,175)
#result = cv2.medianBlur(result,)

cv2.imwrite('mylogin.jpg',result)