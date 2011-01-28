//
//  PushViewControllerAnimatedAppDelegate.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright Kioty Ltd 2010. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

#define kFilename @"GeneralData.plist" 
#define kFilenameTOKEN @"token.plist" 

#define kFilenameGP @"gpDetails.plist" 
#define kFilenamePharmacy @"pharmacyDetails.plist" 
#define kFilenameDental @"dentalDetails.plist" 
#define kFilenameEmergency @"EmergencyDetails.plist" 
#define kFilenameWalkin @"WalkinDetails.plist" 
#define kFilenameHospital @"HospitalDetails.plist" 
#define kFilenameSex @"SexualHealthDetails.plist"

/*
 extern UIWindow * window;
 extern UINavigationController * navigationController ;
 UIImageView * imageView ;
 */

//CUSTOMER INFORMATION: 

NSString * nameGLOBAL ;              // 0 in file Array
NSString * adressGLOBAL ;			 // 1 in file Array
NSString * bloodTypeGLOBAL ;		 // 2 in file Array
NSString * DonorYesNoGLOBAL ;		 // 3 in file Array
NSString * DonorNumberGLOBAL ;		 // 4 in file Array
NSString * NextofKinNameGLOBAL ;	 // 5 in file Array	
NSString * NextofKinNumberGLOBAL ;	 // 6 in file Array
NSString * NextofKinMailGLOBAL ;     // 7 in file Array
NSString * dobGLOBAL ;				 // 8 in file Array
NSString * allergiesGLOBAL ;		 // 9 in file Array
NSString * medicationGLOBAL ;	     // 10 in file Array
NSString * alarmSound ;				 // 11 in file Array
NSString * mynotes ;				 // 12 in file Array
NSString * existingConditions ;		 // 13 in file Array

UIImageView * imagenTermo ;

bool gifLanzado ;

int fromWhere ;

int estado_anterior ;
int estado_siguiente ;

/* INFORMACION PARA LOS ESTADOS:
 
 Funcionamiento: Por defecto, cuando llegue a una nueva ventana, dire q por defecto, se pulsara el boton back. Si 
 el estado siguiente es diferente, sera cada boton el que defina el estado.
 
 -1: Estado siguiente -> Significa que pulsamos boton BACK
 
 0: Begin
 1: Menu principal (view1)
 2: Menu Service Finder (view2)
 3: MAP
 4: Confirmacion WalkinCenter
 
 
 51: Vengo desde los mapas y quiero ir al Browser para el NHS
 61: Acabo de arrancar la aplicacion, voy al service finder y vuelvo, solo eso.

 */ 


UIBarButtonItem * myBarButtonItem ; 

@interface PushViewControllerAnimatedAppDelegate : NSObject < MKMapViewDelegate, UIAlertViewDelegate, UIApplicationDelegate, UINavigationControllerDelegate> {
	
@public
	
	
	MKMapView * mapaMain ;
	
	UIWindow * window;
	UINavigationController * navigationController ;
	UIImageView * imageView  ;
	
	UIBarButtonItem * homeButton ; 
	
	int var ;		
	
	NSString * tokenLeo ;
	
	
}


@property (readwrite, retain) IBOutlet NSString * nameGLOBAL ;


@property (readwrite, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet UINavigationController * navigationController ;
//@property (nonatomic, retain) IBOutlet UIImageView * imageView ;
- (NSString *) dataFilePath ;
- (NSString *) dataFilePathTOKEN ;

@end

