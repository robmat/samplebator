//
//  ALARMView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 01/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>
#import <MapKit/MKReverseGeocoder.h>


@interface ALARMView : UIViewController<MKMapViewDelegate,MKReverseGeocoderDelegate> {

	NSMutableArray * lista ;
	
	NSMutableArray * states ;
	NSMutableArray * capitals ;
	
	IBOutlet UILabel * textLabel ;	
	
	IBOutlet UIView * myView ;
	
	//UILabel * tempoLabel ;
	
	IBOutlet UITableView * myTableView ;
	
	NSString * postcode ;
	NSString * city ;
	
//	IBOutlet UILabel * labelTurnAlarm ;
	IBOutlet UIBarButtonItem * buttonAlarmTurn ;
	
	IBOutlet UIImageView * theAnimatedGif;

	IBOutlet UILabel * nameText ;
	IBOutlet UILabel * addressText ;
	IBOutlet UILabel * bloodText ;
	
	IBOutlet UILabel * numberDonorText ;

	MKMapView   *  mapa ;
	
    MKReverseGeocoder *reverseGeocoder;
	
	UIBarButtonItem * sendSMSButton ;
	IBOutlet UIView * view2 ;
	
	IBOutlet UITextView * textView2 ;
	
	IBOutlet UILabel * tituloNewView ;
	IBOutlet UITextView * textViewNewView ;

	NSString * tempoText ;
	
	BOOL mostrarAlerta ;
	BOOL volver ; 
	
	IBOutlet UIImageView * imageRedBackground ;
	
	BOOL aumentar ;
	float topeMAX  ;
	float topeMIN  ;
	NSTimer* myTimer;
	
	IBOutlet UIView * vistaNewView ;
	IBOutlet UILabel * mainText ;
	IBOutlet UITextView * insideText ;
	
	BOOL call999boolean ;

}


@property (nonatomic, retain) IBOutlet MKMapView * mapa;
@property (nonatomic, retain) MKReverseGeocoder * reverseGeocoder;
@property (nonatomic, retain) IBOutlet UIBarButtonItem * sendSMSButton;
@property (nonatomic,retain) 	NSMutableArray * lista ;

-(IBAction)smsContinueAction ;
-(NSString *) dataFilePath ;
-(IBAction) buttonAlarmTurnAction ;

-(IBAction)buttonTest ;

-(IBAction)sendSMS ;
-(IBAction)	buttonMedication:(id)sender ;
-(IBAction)changeColourBackgroundAction:(id)sender ;
-(IBAction)	buttonAllergies:(id)sender ;
-(IBAction) existingConditions:(id)sender ;

-(IBAction)call999 ;

-(IBAction)closeNewView ;

@end
