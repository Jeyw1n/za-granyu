from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
from ultralytics import YOLO
import shutil
import os

app = FastAPI()

# Загружаем модель один раз при старте приложения
model = YOLO('weights.pt')

def detect_objects(image_path):
    # Выполняем предсказание
    results = model.predict(source=image_path, conf=0.25)

    # Извлекаем лейблы и координаты
    detections = []
    for result in results:
        for box in result.boxes:
            x1, y1, x2, y2 = box.xyxy[0]  # Координаты бокса
            label = result.names[int(box.cls)]  # Имя класса
            detections.append((label, x1))  # Сохраняем лейбл и координату x1

    # Сортируем по координате X (x1)
    detections.sort(key=lambda x: x[1])  # Сортируем по x1

    # Извлекаем только лейблы и преобразуем в строку
    sorted_labels = ' '.join(label for label, _ in detections)

    return sorted_labels

@app.post("/detect/")
async def detect(file: UploadFile = File(...)):
    # Сохраняем загруженный файл
    file_location = f"temp/{file.filename}"
    with open(file_location, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    # Выполняем обнаружение объектов
    labels = detect_objects(file_location)

    # Удаляем временный файл
    os.remove(file_location)

    return JSONResponse(content={"labels": labels})

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)

