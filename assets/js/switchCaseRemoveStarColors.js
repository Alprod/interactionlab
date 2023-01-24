const switchCaseZero = (item, y, y10, y50) => {
  item?.forEach((i) => {
    i.classList.remove(y)
    i.classList.remove(y50)
    i.classList.add(y10)
  })
}

const switchCaseDecimal = (item, y, y10, y50) => {
  item.classList.remove(y);
  item.classList.remove(y10);
  item.classList.add(y50);
}

const switchCase = (item, y, y10, y50) => {
  item.classList.remove(y50);
  item.classList.remove(y10);
  item.classList.add(y);
}
const switchCase1 = (item, y, y10, y50) => {
  item.classList.remove(y);
  item.classList.remove(y50);
  item.classList.add(y10);
}
const switchCaseInt10 = (item,item1, y, y10, y50) => {
  item.classList.remove(y10)
  item.classList.add(y)
  item1.classList.remove(y50)
  item1.classList.add(y10)
}
const switchCaseInt50 = (item,item1, y, y10, y50) => {
  item.classList.remove(y50);
  item.classList.add(y);
  item1.classList.remove(y50)
  item1.classList.add(y10)
}

const switchColors = (arrayValue, targetValue, c, c1, c2) => {
  switch (true) {
    case targetValue <= 0 :
      switchCaseZero(arrayValue, c, c1, c2)
      break
    case targetValue >= 0.1 && targetValue <= 0.8 :
      arrayValue[0].classList.remove(c)
      arrayValue[0].classList.add(c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCase1(arrayValue[2], c, c1, c2)
      switchCase1(arrayValue[1], c, c1, c2)
      break
    
    case targetValue >= 0.9 && targetValue <= 1 :
      switchCase1(arrayValue[4], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCase1(arrayValue[2], c, c1, c2)
      switchCase1(arrayValue[1], c, c1, c2)
      switchCaseInt10(arrayValue[0], arrayValue[1], c, c1, c2)
      break
    
    case targetValue >= 1.1 && targetValue <= 1.8 :
      switchCase(arrayValue[0], c, c1, c2)
      switchCaseDecimal(arrayValue[1], c, c1, c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCase1(arrayValue[2], c, c1, c2)
      break
    case targetValue >= 1.9 && targetValue <= 2:
      switchCase(arrayValue[0], c,c1,c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCase1(arrayValue[2], c, c1, c2)
      switchCaseInt50(arrayValue[1], arrayValue[2], c, c1, c2)
      break;
    
    case targetValue >= 2.1 && targetValue <= 2.8 :
      switchCase(arrayValue[0], c, c1, c2)
      switchCase(arrayValue[1], c, c1, c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCaseDecimal(arrayValue[2],c,c1,c2)
      break;
    
    case targetValue >= 2.9 && targetValue <= 3:
      switchCase(arrayValue[0], c, c1, c2)
      switchCase(arrayValue[1], c, c1, c2)
      switchCase(arrayValue[2], c, c1, c2)
      switchCase1(arrayValue[3], c, c1, c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCaseInt50(arrayValue[2], arrayValue[3], c, c1, c2)
      break;
    
    case targetValue >= 3.1 && targetValue <= 3.8:
      switchCase(arrayValue[0], c, c1, c2)
      switchCase(arrayValue[1], c, c1, c2)
      switchCase(arrayValue[2], c, c1, c2)
      switchCase1(arrayValue[4], c, c1, c2)
      switchCaseDecimal(arrayValue[3],c,c1,c2)
      break;
    
    case targetValue >= 3.9 && targetValue <=4:
      switchCase(arrayValue[0], c, c1, c2)
      switchCase(arrayValue[1], c, c1, c2)
      switchCase(arrayValue[2], c, c1, c2)
      switchCaseInt50(arrayValue[3], arrayValue[4], c, c1, c2)
      break;
    
    case targetValue >= 4.1 && targetValue <= 4.8:
      switchCase(arrayValue[0], c, c1, c2)
      switchCase(arrayValue[1], c, c1, c2)
      switchCase(arrayValue[2], c, c1, c2)
      switchCase(arrayValue[3], c, c1, c2)
      switchCaseDecimal(arrayValue[4],c,c1,c2)
      break;
    case targetValue >= 4.9:
      arrayValue.forEach((e) => {
        e.classList.remove(c2);
        e.classList.add(c)
      })
      break;
  }
}
export {
  switchColors,
  switchCaseZero,
  switchCase,
  switchCase1,
  switchCaseInt10,
  switchCaseInt50,
  switchCaseDecimal}