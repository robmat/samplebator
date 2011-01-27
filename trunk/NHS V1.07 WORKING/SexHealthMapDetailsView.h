//
//  SexHealthMapDetailsView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 10/08/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>

NSString * informacion ;

@interface SexHealthMapDetailsView : UIViewController {
	
	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	
	IBOutlet UITextView * labelPhone ;
	
	IBOutlet UILabel * labelAddress1b;
	NSString* openingTimes;
}

@property (nonatomic, copy) NSString* openingTimes;

-(NSString *) dataFilePathSex ;
//-(IBAction)makeAppointmentButton ;
-(IBAction)sendMail:(id)sender ;
-(IBAction)showOpeningTimes:(id)sender;

@end
