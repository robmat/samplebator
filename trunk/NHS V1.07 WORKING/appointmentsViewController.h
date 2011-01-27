//
//  appointmentsViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface appointmentsViewController : UIViewController<UITabBarControllerDelegate, UITabBarDelegate> {

	NSDate * appintmentDate ;
	NSString * dateString ;
	
	IBOutlet UILabel * serviceLabel ;
	IBOutlet UILabel * appointmentTime ;
	IBOutlet UILabel * appointmentBefore ;
	
	IBOutlet UIButton * textoSetReminder ;
	
	IBOutlet UITabBar * rootTabBarController ;

	IBOutlet UITabBarItem * buttonA ;
	IBOutlet UITabBarItem * buttonB ;
	
	//IBOutlet UITabBarItem * buttonB ;
	//IBOutlet UITabBarItem * buttonC ;
	
	IBOutlet UIView * viewAddButton;
	IBOutlet UIView * viewListButton ;
	IBOutlet UIView * viewEmailButton ;
	
	IBOutlet UIButton * emergenciesButton ;
	IBOutlet UIButton * gpButton;
	IBOutlet UIButton * hospitalButton ;
	IBOutlet UIButton * dentistButton ;
	
	IBOutlet UIButton * min30Button;
	IBOutlet UIButton * min60Button ;
	IBOutlet UIButton * min90Button ;
	IBOutlet UIButton * hour24Button ;
	
	IBOutlet UIDatePicker * datePicker ;

}

-(IBAction)butonAddAction ;
-(IBAction)buttonAAction ; 
-(NSString *) dataFilePathTOKEN ;

-(IBAction)sendReminder ; 
-(IBAction)setReminderAction ;

-(IBAction)emergenciesButtonAction ;
-(IBAction)hospitalButtonAction ;
-(IBAction)gpButtonAction ;
-(IBAction)dentistButtonAction ;

-(IBAction)min30ButtonAction ;
-(IBAction)min60ButtonAction ;
-(IBAction)min90ButtonAction ;
-(IBAction)hor24ButtonAction ;


//@property (readwrite,nonatomic) IBOutlet IBOutlet UIButton * textoSetReminder ;

@end
