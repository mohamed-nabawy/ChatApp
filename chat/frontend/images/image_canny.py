import cv2
import numpy as np
from matplotlib import pyplot as plt

img = cv2.imread('login2.jpg')

# step 1 : scale original image
scaledImage = cv2.resize(img,None,fx=4, fy=3, interpolation = cv2.INTER_CUBIC)

# step 2 : canny detector on the original
edges = cv2.Canny(img,100,150)

# step 3 : scale the result of edge detector (same scale used in step 1)
scaledEdges = cv2.resize(edges,None,fx=4,fy=3,interpolation = cv2.INTER_CUBIC)
kernel = np.ones((9,9),np.uint8)
scaledEdges = cv2.dilate(scaledEdges,kernel,iterations = 1)

# step 4 : convert result of scaling the edges to rgb
scaledEdges = cv2.cvtColor(scaledEdges,cv2.COLOR_GRAY2RGB)

# step 5 : sum the scaled original image and the scaled edges
result = scaledImage + scaledEdges

# step 6 : apply a filter to remove any noise
result = cv2.medianBlur(result,21)

cv2.imwrite('mylogin.jpg',result)