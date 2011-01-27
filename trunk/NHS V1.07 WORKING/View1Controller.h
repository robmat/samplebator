//
//  View1Controller.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>
#import "InWhichCountyAmI.h"
#import "GeoLocation.h"

int valorB ;

@interface View1Controller : UIViewController<MKMapViewDelegate, IGeoAware, UIAlertViewDelegate> {
	
	IBOutlet UIImageView * defaultImage ;
	IBOutlet UILabel * NHSBristolLabel ;
	IBOutlet UILabel * loadingLabel ;
	IBOutlet UIActivityIndicatorView * activity ;
	
	IBOutlet UIImageView * imageLoading ; 
	
	IBOutlet UIImageView * imageBar ;
	IBOutlet UIView * mainView ;
	IBOutlet UIView * viewBar ;
	
	IBOutlet UIView * barNHS ;
	
	IBOutlet UIImage * imageNHS ; 
	
	IBOutlet MKMapView * mapa ;
	
	NSTimer * myTimer;
	
	IBOutlet UIButton *  SOSBut ;
	IBOutlet UIButton *  ServicesBut ;
	IBOutlet UIButton *  NHSDirectBut;
	IBOutlet UIButton *  PersonalBut ;
	IBOutlet UIButton *  RemindersBut ;
	IBOutlet UIButton *  ContactNHSBut  ;	
	
	IBOutlet UIView * helpView ;
	IBOutlet UIView * loadingView ;
	
	IBOutlet UIButton * showMeDoctorHelpButton ;
	IBOutlet UIButton * showMeDoctorHelpButton2 ;
	IBOutlet UIButton * yorkshireUrgentBtn;
	IBOutlet UIButton * helpMeLabel ;
	
	IBOutlet UILabel * tempoLabel ;
	
	IBOutlet UITextView * helpTexto;
	IBOutlet UIButton * closeHelpText ;
	IBOutlet UIBarButtonItem * closeViewText ;
	IBOutlet UIToolbar * toolbar ;

}

-(IBAction)HelpMeButton:(id)sender;
-(IBAction)ServiceFinderButton:(id)sender;
-(IBAction)NHSDirectButton:(id)sender ;
-(IBAction)PersonalButton:(id)sender ;
-(IBAction)AppointmentsButton:(id)sender ;
-(IBAction)contactNHSView:(id)sender ;
-(IBAction)showMeDoctorHelpAction:(id)sender ;

-(IBAction)showMeDoctorHelpMeYES:(id)sender ;
-(IBAction)showMeDoctorHelpMeNO:(id)sender ;

-(IBAction)goHome:(id)sender ;
-(IBAction) luanchBrowser:(id)sender;

-(IBAction) updatGeoLocation:(id)sender;

@end
