//
//  editDOBViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editDOBViewController : UIViewController <UIPickerViewDelegate, UIPickerViewDataSource>{

	NSDate * appintmentDate ;
	NSString * dateString ;
	
	IBOutlet UIDatePicker * datePicker ;
	IBOutlet UILabel * dateLabel ; 
	
}

-(IBAction)saveAction:(id)sender ;
-(NSString *) dataFilePath ;

@end
